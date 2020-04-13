<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\Color as ColorObject;

class Color extends AbstractDecoder
{
    /**
     * Decode current source to Color
     *
     * @return ColorObject
     */
    public function decode(): ColorObject
    {
        $color = new ColorObject(0, 0, 0);

        $color->setRed($this->decodeRed());
        $color->setGreen($this->decodeGreen());
        $color->setBlue($this->decodeBlue());

        return $color;
    }

    /**
     * Decode red value from source
     *
     * @return int
     */
    protected function decodeRed(): int
    {
        return unpack('C', substr($this->source, 0, 1))[1];
    }

    /**
     * Decode red value from source
     *
     * @return int
     */
    protected function decodeGreen(): int
    {
        return unpack('C', substr($this->source, 1, 1))[1];
    }

    /**
     * Decode red value from source
     *
     * @return int
     */
    protected function decodeBlue(): int
    {
        return unpack('C', substr($this->source, 2, 1))[1];
    }
}
