<?php
namespace Snowplow\RefererParser\Library\Tests;

use Snowplow\RefererParser\Library\Config\JsonConfigReader;
use Snowplow\RefererParser\Library\Parser;

class JsonParserTest extends AbstractParserTest
{
    protected function createParser(array $internalHosts = [])
    {
        return new Parser(new JsonConfigReader(__DIR__ . '/../../../../data/referers.json'), $internalHosts);
    }
}
