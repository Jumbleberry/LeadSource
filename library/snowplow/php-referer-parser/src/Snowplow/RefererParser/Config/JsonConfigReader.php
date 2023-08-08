<?php

namespace Snowplow\RefererParser\Library\Config;

use Serializable;

class JsonConfigReader implements ConfigReaderInterface, Serializable
{
    use ConfigFileReaderTrait {
        ConfigFileReaderTrait::init as public __construct;
    }

    protected function parse($content)
    {
        return json_decode($content, true);
    }

    public function serialize(): string
    {
        return function_exists('igbinary_serialize')
            ? \igbinary_serialize($this->__serialize())
            : serialize($this->__serialize());
    }

    public function __serialize(): array
    {
        return $this->referers;
    }

    public function unserialize($data): void
    {
        $this->__unserialize(
            substr($data, 0, 3) === "\x00\x00\x00"
                ? \igbinary_unserialize($data)
                : unserialize($data)
        );
    }

    public function __unserialize(array $serialized): void
    {
        $this->referers = $serialized;
    }
}
