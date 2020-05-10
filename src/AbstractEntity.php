<?php

namespace Intervention\Gif;

use Intervention\Gif\Traits\CanDecode;
use Intervention\Gif\Traits\CanEncode;
use ReflectionClass;

abstract class AbstractEntity
{
    use CanEncode;
    use CanDecode;

    public const TERMINATOR = "\x00";

    /**
     * Get short classname of current instance
     *
     * @return string
     */
    public static function getShortClassname(): string
    {
        return (new ReflectionClass(get_called_class()))->getShortName();
    }
}
