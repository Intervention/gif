<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Color;

class ColorDecoder extends AbstractDecoder
{
    /**
     * Decode current source to Color
     *
     * @return Color
     */
    public function decode(): Color
    {
        $color = new Color();

        $color->setRed($this->decodeColorValue($this->getNextByte()));
        $color->setGreen($this->decodeColorValue($this->getNextByte()));
        $color->setBlue($this->decodeColorValue($this->getNextByte()));

        return $color;
    }

    /**
     * Decode red value from source
     *
     * @return int
     */
    protected function decodeColorValue(string $byte): int
    {
        return unpack('C', $byte)[1];
    }
}
