<?php

declare(strict_types=1);

namespace Intervention\Gif\Traits;

use Intervention\Gif\Decoders\AbstractDecoder;
use Intervention\Gif\Exceptions\DecoderException;

trait CanDecode
{
    /**
     * Decode current instance
     *
     * @throws DecoderException
     */
    public static function decode(mixed $source, ?int $length = null): mixed
    {
        return self::getDecoder($source, $length)->decode();
    }

    /**
     * Get decoder for current instance
     *
     * @throws DecoderException
     */
    protected static function getDecoder(mixed $source, ?int $length = null): AbstractDecoder
    {
        $classname = self::getDecoderClassname();

        if (!class_exists($classname)) {
            throw new DecoderException("Decoder for '" . static::class . "' not found.");
        }

        return new $classname($source, $length);
    }

    /**
     * Get classname of decoder for current classname
     */
    protected static function getDecoderClassname(): string
    {
        return sprintf('Intervention\Gif\Decoders\%sDecoder', self::getShortClassname());
    }
}
