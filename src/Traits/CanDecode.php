<?php

namespace Intervention\Gif\Traits;

use Exception;
use ReflectionClass;
use Intervention\Gif\Decoder\AbstractDecoder;
use Intervention\Gif\AbstractEntity;

trait CanDecode
{
    /**
     * Decode current instance
     *
     * @param  string $source
     * @return AbstractEntity
     */
    public static function decode(string $source): AbstractEntity
    {
        return self::getDecoder($source)->decode();
    }

    /**
     * Get decoder for current instance
     *
     * @param  string $source
     * @return AbstractDecoder
     */
    protected static function getDecoder(string $source): AbstractDecoder
    {
        $classname = self::getDecoderClassname();

        if (!class_exists($classname)) {
            throw new Exception("Decoder for '".get_called_class()."' not found.");
        }

        return new $classname($source);
    }

    /**
     * Get classname of decoder for current classname
     *
     * @return string
     */
    protected static function getDecoderClassname(): string
    {
        return sprintf('Intervention\Gif\Decoder\%s', self::getShortClassname());
    }
}
