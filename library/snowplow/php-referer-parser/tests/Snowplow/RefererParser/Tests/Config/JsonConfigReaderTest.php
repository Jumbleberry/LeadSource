<?php
namespace Snowplow\RefererParser\Library\Tests\Config;

use Snowplow\RefererParser\Library\Config\JsonConfigReader;

class JsonConfigReaderTest extends AbstractConfigReaderTest
{
    protected function createConfigReader($fileName)
    {
        return new JsonConfigReader($fileName);
    }
    
    protected function createConfigReaderFromFile()
    {
        return $this->createConfigReader(__DIR__ . '/../../../../../data/referers.json');
    }
}
