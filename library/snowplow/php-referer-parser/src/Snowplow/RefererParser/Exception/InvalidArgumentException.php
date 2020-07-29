<?php
namespace Snowplow\RefererParser\Library\Exception;

use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidArgumentException extends BaseInvalidArgumentException
{
    public static function fileNotExists($fileName)
    {
        return new static(sprintf('File "%s" does not exist', $fileName));
    }
}
