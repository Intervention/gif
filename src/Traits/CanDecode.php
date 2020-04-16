<?php

namespace Intervention\Gif\Traits;

use Exception;
use Closure;
use ReflectionClass;
use Intervention\Gif\Decoder\AbstractDecoder;
use Intervention\Gif\AbstractEntity;

trait CanDecode
{
    /**
     * Decode current instance
     *
     * @param  resource     $source
     * @param  null|Closure $callback
     * @return AbstractEntity
     */
    public static function decode($source, ?Closure $callback = null): AbstractEntity
    {
        return self::getDecoder($source, $callback)->decode();
    }

    /**
     * Get decoder for current instance
     *
     * @param  resource          $source
     * @param  null|Closure      $callback
     * @return AbstractDecoder
     */
    protected static function getDecoder($source, Closure $callback = null): AbstractDecoder
    {
        $classname = self::getDecoderClassname();

        if (!class_exists($classname)) {
            throw new Exception("Decoder for '".get_called_class()."' not found.");
        }

        return new $classname($source, $callback);
    }

    /**
     * Get classname of decoder for current classname
     *
     * @return string
     */
    protected static function getDecoderClassname(): string
    {
        return sprintf('Intervention\Gif\Decoder\%sDecoder', self::getShortClassname());
    }
}
