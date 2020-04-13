<?php

namespace Intervention\Gif;

use Intervention\Gif\Traits\CanDecode;
use Intervention\Gif\Traits\CanEncode;
use ReflectionClass;

abstract class AbstractEntity
{
    use CanEncode, CanDecode;

    /**
     * Get short classname of current instance
     *
     * @return string
     */
    public function getShortClassname(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }
}
