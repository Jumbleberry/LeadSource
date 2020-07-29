<?php
namespace Snowplow\RefererParser\Library\Tests;

use Snowplow\RefererParser\Library\Parser;

class DefaultParserTest extends AbstractParserTest
{
    protected function createParser(array $internalHosts = [])
    {
        return new Parser(null, $internalHosts);
    }
}
