<?php
namespace Snowplow\RefererParser\Library\Config;

interface ConfigReaderInterface
{
    /**
     * @param string $lookupString
     * @return array
     */
    public function lookup($lookupString);
}
