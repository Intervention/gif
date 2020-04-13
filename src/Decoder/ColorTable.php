<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Color;
use Intervention\Gif\ColorTable as ColorTableObject;
use Intervention\Gif\Decoder\Color as ColorDecoder;
use Intervention\Gif\Exception\DecoderException;

class ColorTable extends AbstractDecoder
{
    /**
     * Decode given string to ColorTable
     *
     * @param  string $source
     * @return ColorTableObject
     */
    public function decode(): ColorTableObject
    {
        $table = new ColorTableObject;
        foreach (str_split($this->source, 3) as $value) {
            $table->addColor($this->decodeColor($value));
        }

        return $table;
    }

    /**
     * Decode Color
     *
     * @param  string $source
     * @return Color
     */
    private function decodeColor(string $source): Color
    {
        return (new ColorDecoder($source))->decode();
    }
}
