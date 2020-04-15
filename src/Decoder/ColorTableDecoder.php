<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Color;
use Intervention\Gif\ColorTable;
use Intervention\Gif\Exception\DecoderException;

class ColorTableDecoder extends AbstractDecoder
{
    /**
     * Decode given string to ColorTable
     *
     * @param  string $source
     * @return ColorTable
     */
    public function decode(): ColorTable
    {
        $table = new ColorTable;
        foreach (str_split($this->source, 3) as $colorvalue) {
            $table->addColor(Color::decode($colorvalue));
        }

        return $table;
    }
}
