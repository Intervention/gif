<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\ColorTable as ColorTableObject;

class ColorTable extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param ColorTableObject $source
     */
    public function __construct(ColorTableObject $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     *
     * @param  ColorTableObject $table
     * @return string
     */
    public function encode(): string
    {
        return implode('', array_map(function ($color) {
            return $color->encode();
        }, $this->source->getColors()));
    }
}
