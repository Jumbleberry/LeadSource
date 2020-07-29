<?php
namespace Library\Snowplow\RefererParser\Tests;

use Library\Snowplow\RefererParser\Config\JsonConfigReader;
use Library\Snowplow\RefererParser\Parser;

class JsonParserTest extends AbstractParserTest
{
    protected function createParser(array $internalHosts = [])
    {
        return new Parser(new JsonConfigReader(__DIR__ . '/../../../../data/referers.json'), $internalHosts);
    }
}
