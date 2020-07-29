<?php
namespace Library\Snowplow\RefererParser\Tests;

use Library\Snowplow\RefererParser\Parser;

class DefaultParserTest extends AbstractParserTest
{
    protected function createParser(array $internalHosts = [])
    {
        return new Parser(null, $internalHosts);
    }
}
