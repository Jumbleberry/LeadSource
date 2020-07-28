<?php

namespace JBX\RefererParser;

use Snowplow\RefererParser\Config\ConfigReaderInterface;
use Snowplow\RefererParser\Medium;
use Snowplow\RefererParser\Parser;
use Snowplow\RefererParser\Referer;

class SnowplowParser extends Parser
{
    public $additionalConfig;

    public function __construct(ConfigReaderInterface $configReader = null, array $internalHosts = [])
    {
        parent::__construct($configReader, $internalHosts);
        $this->additionalConfig = static::createAdditionalConfig();
    }

    public function parseReferrer($pageReferrer = null, $pageUrl = null, $useragent = null)
    {
        $referrer = $this->parseReferrerUrl($pageReferrer, $pageUrl);

        if (!$referrer->isKnown() && $useragent) {
            $referrer = $this->parseUseragent($useragent);
        }

        return $referrer;
    }

    public function parseReferrerUrl($pageReferrer = null, $pageUrl = null)
    {
        if ($pageReferrer || $pageUrl) {
            $referrer = parent::parse($pageReferrer, $pageUrl);
            return $referrer->isKnown() ? $referrer : ($this->parseUrlQuery($pageReferrer, $pageUrl));
        }
    }

    //overwrite method to add 'android-app' as scheme
    protected static function parseUrl($url)
    {
        if ($url === null) {
            return null;
        }

        $parts = parse_url($url);
        if (!isset($parts['scheme']) || !in_array(strtolower($parts['scheme']), ['http', 'https', 'android-app'])) {
            return null;
        }

        return array_merge(['query' => null, 'path' => '/'], $parts);
    }

    public function parseUseragent($useragent)
    {
        if ($useragent) {
            foreach ($this->additionalConfig as $key => $params) {
                if (isset($params['regexes'])) {
                    foreach ($params['regexes'] as $regex) {
                        if (preg_match('/' . str_replace('/', '\/', str_replace('\/', '/', $regex)) . '/i', $useragent, $matches)) {
                            return Referer::createKnown($params['medium'], $key, '');
                        }
                    }
                }
            }
        }

        return Referer::createUnknown();
    }

    protected function parseUrlQuery($referrerUrl, $pageUrl)
    {
        $query1 = explode('&', parse_url($referrerUrl, PHP_URL_QUERY));
        $query2 = explode('&', parse_url($pageUrl, PHP_URL_QUERY));

        $queryParams = array_map(function ($param) {
            return explode('=', $param)[0];
        }, array_merge($query1, $query2));

        foreach ($this->additionalConfig as $key => $params) {
            if (isset($params['identifier']) && in_array($params['identifier'], $queryParams)) {
                return Referer::createKnown($params['medium'], $key, '');
            }
        }

        return Referer::createUnknown();
    }

    protected static function createAdditionalConfig()
    {
        $file = file_get_contents('data/referers.json');
        return json_decode($file, true);
    }
}