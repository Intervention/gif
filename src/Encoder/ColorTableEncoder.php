<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\Blocks\ColorTable;

class ColorTableEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param ColorTable $source
     */
    public function __construct(protected ColorTable $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     *
     * @return string
     */
    public function encode(): string
    {
        return implode('', array_map(function ($color) {
            return $color->encode();
        }, $this->source->getColors()));
    }
}
