<?php
namespace Library\Snowplow\RefererParser\Tests\Config;

use Library\Snowplow\RefererParser\Config\JsonConfigReader;

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
