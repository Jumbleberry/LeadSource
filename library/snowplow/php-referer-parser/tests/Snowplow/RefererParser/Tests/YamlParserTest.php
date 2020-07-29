<?php
namespace Snowplow\RefererParser\Library\Tests;

use Snowplow\RefererParser\Library\Config\YamlConfigReader;
use Snowplow\RefererParser\Library\Parser;

class YamlParserTest extends AbstractParserTest
{
    private static $reader;

    public static function setUpBeforeClass()
    {
        static::$reader = new YamlConfigReader(__DIR__ . '/../../../../data/referers.yml');
    }

    protected function createParser(array $internalHosts = [])
    {
        return new Parser(static::$reader, $internalHosts);
    }
}
