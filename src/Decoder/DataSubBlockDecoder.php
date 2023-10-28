<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\DataSubBlock;

class DataSubBlockDecoder extends AbstractDecoder
{
    /**
     * Decode current sourc
     *
     * @return DataSubBlock
     */
    public function decode(): DataSubBlock
    {
        $char = $this->getNextByte();
        $size = (int) unpack('C', $char)[1];

        return new DataSubBlock($this->getNextBytes($size));
    }
}
