<?php

declare(strict_types=1);

namespace Intervention\Gif;

use Intervention\Gif\Traits\CanDecode;
use Intervention\Gif\Traits\CanEncode;
use ReflectionClass;
use ReflectionException;
use Stringable;

abstract class AbstractEntity implements Stringable
{
    use CanEncode;
    use CanDecode;

    public const TERMINATOR = "\x00";

    /**
     * Get short classname of current instance
     */
    public static function getShortClassname(): ?string
    {
        try {
            return (new ReflectionClass(static::class))->getShortName();
        } catch (ReflectionException) {
            return null;
        }
    }

    /**
     * Cast object to string
     */
    public function __toString(): string
    {
        return $this->encode();
    }
}
