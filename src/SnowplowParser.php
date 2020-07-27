<?php

namespace JBX\RefererParser;

use Snowplow\RefererParser\Config\ConfigReaderInterface;
use Snowplow\RefererParser\Parser;

class SnowplowParser extends Parser
{
    public $additionalConfig;

    public function __construct(ConfigReaderInterface $configReader = null, array $internalHosts = [])
    {
        parent::__construct($configReader, $internalHosts);
        $this->additionalConfig = static::createAdditionalConfigReader();
    }

    public function parseReferrer($page_referrer = null, $page_url = null, $useragent = null)
    {
        $referrer = $this->parseReferrerUrl($page_referrer, $page_url);

        if (!$referrer && $useragent) {
            $referrer = $this->parseUseragent($useragent);
        }
        return $referrer;
    }

    public function parseReferrerUrl($page_referrer = null, $page_url = null)
    {
        if ($page_referrer || $page_url) {
            return parent::parse($page_referrer, $page_url)->getSource() ?? $this->parseUrlQuery($page_referrer, $page_url);
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
    }

    protected function parseUrlQuery($referrerUrl, $pageUrl)
    {
        $query1 = explode('&', parse_url($referrerUrl, PHP_URL_QUERY));
        $query2 = explode('&', parse_url($pageUrl, PHP_URL_QUERY));

        $queryParams = array_map(function ($param) {
            return explode('=', $param)[0];
        }, array_merge($query1, $query2));

        foreach ($this->additionalConfig as $key => $params) {
            if (isset($params['identifiers']) && in_array($params['identifiers'], $queryParams)) {
                return $key;
            }
        }
    }

    protected static function createAdditionalConfigReader()
    {
        $file = file_get_contents('data/referers.json');
        return json_decode($file, true);
    }
}