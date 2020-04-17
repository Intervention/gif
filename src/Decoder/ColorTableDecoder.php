<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\Color;
use Intervention\Gif\ColorTable;
use Intervention\Gif\Exception\DecoderException;

class ColorTableDecoder extends AbstractDecoder
{
    /**
     * Decode given string to ColorTable
     *
     * @return AbstractEntity
     */
    public function decode(): AbstractEntity
    {
        $table = new ColorTable;
        for ($i=0; $i < ($this->getLength() / 3); $i++) {
            $table->addColor(Color::decode($this->handle));
        }

        return $table;
    }
}
