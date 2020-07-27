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

        if (!$referrer && $useragent) {
            $referrer = $this->parseUseragent($useragent);
        }
        return $referrer;
    }

    public function parseReferrerUrl($pageReferrer = null, $pageUrl = null)
    {
        if ($pageReferrer || $pageUrl) {
            return parent::parse($pageReferrer, $pageUrl)->getSource() ?? ($this->parseUrlQuery($pageReferrer, $pageUrl))->getSource();
        }
    }

    public function parseUseragent($useragent)
    {
        if ($useragent) {
            foreach ($this->additionalConfig as $key => $params) {
                if (isset($params['regex'])
                    && preg_match('/' . str_replace('/', '\/', str_replace('\/', '/', $params['regex'])) . '/i', $useragent, $matches)
                ) {
                    return $key;
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
                return Referer::createKnown(Medium::UNKNOWN, $key, '');
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