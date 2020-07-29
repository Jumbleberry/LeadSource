<?php
namespace Snowplow\RefererParser\Library\Tests\Config;

use Snowplow\RefererParser\Library\Config\YamlConfigReader;

class YamlConfigReaderTest extends AbstractConfigReaderTest
{
    protected function createConfigReader($fileName)
    {
        return new YamlConfigReader($fileName);
    }
    
    protected function createConfigReaderFromFile()
    {
        return $this->createConfigReader(__DIR__ . '/../../../../../data/referers.yml');
    }
}
