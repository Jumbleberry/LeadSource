<?php

namespace Jumbleberry\LeadSource;

use Snowplow\RefererParser\Library\Config\ConfigReaderInterface;
use Snowplow\RefererParser\Library\Parser as RefererParser;
use Snowplow\RefererParser\Library\Referer;

class Parser extends RefererParser
{
    protected $additionalConfig;

    public function __construct(ConfigReaderInterface $configReader = null, array $internalHosts = [])
    {
        parent::__construct($configReader, $internalHosts);
        $this->additionalConfig = static::getAdditionalConfig();
    }

    public function parseLeadSource($pageReferrer = null, $pageUrl = null, $useragent = null)
    {
        $source = $this->parseReferrer($pageReferrer, $pageUrl, $useragent);
        return $source->getSource() ?: 'Other';
    }

    public function parseReferrer($pageReferrer = null, $pageUrl = null, $useragent = null)
    {
        $referrer = $this->parseReferrerUrl($pageReferrer, $pageUrl);

        if ($referrer && !empty($referrer->getSource())) {
            return $referrer;
        } else if (!empty($useragent)) {
            return $this->parseUseragent($useragent);
        }

        return Referer::createUnknown();
    }

    public function parseReferrerUrl($pageReferrer = null, $pageUrl = null)
    {
        if ($pageReferrer || $pageUrl) {
            $referrer = parent::parse($pageReferrer, $pageUrl);

            if (empty($referrer->getSource())) {
                return $this->parseUrlQuery($pageReferrer, $pageUrl);
            }

            return $referrer;
        }
    }

    public function parseUseragent($useragent)
    {
        foreach ($this->additionalConfig as $medium => $sources) {
            foreach ($sources as $sourceName => $sourceParam) {
                if (isset($sourceParam['regexes'])) {
                    foreach ($sourceParam['regexes'] as $regex) {
                        if (preg_match('~' . $regex . '~i', $useragent, $matches)) {
                            return Referer::createKnown($medium, $sourceName, '');
                        }
                    }
                }
            }
        }

        return Referer::createUnknown();
    }

    protected function parseUrlQuery($referrerUrl, $pageUrl)
    {
        $referrerUrlQuery = strtolower(parse_url($referrerUrl, PHP_URL_QUERY));
        $pageUrlQuery     = strtolower(parse_url($pageUrl, PHP_URL_QUERY));
        $query            = $referrerUrlQuery . $pageUrlQuery;

        parse_str($referrerUrlQuery, $query1);
        parse_str($pageUrlQuery, $query2);
        $queryParams = array_merge($query1, $query2);

        foreach ($this->additionalConfig as $medium => $sources) {
            foreach ($sources as $sourceName => $sourceParam) {
                if (isset($sourceParam['identifier'])) {
                    $identifiers = is_array($sourceParam['identifier']) ? $sourceParam['identifier'] : [$sourceParam['identifier']];
                    foreach ($identifiers as $identifier) {
                        if ((array_key_exists($identifier, $queryParams) || strpos($query, $identifier) !== false)) {
                            return Referer::createKnown($medium, $sourceName, '');
                        }
                    }
                }
            }
        }

        return Referer::createUnknown();
    }

    protected static function getAdditionalConfig()
    {
        $file = file_get_contents(__DIR__ . '/../data/referers.json', true);
        return json_decode($file, true);
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
}
