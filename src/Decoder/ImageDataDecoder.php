<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\ImageData;

class ImageDataDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return AbstractEntity
     */
    public function decode(): AbstractEntity
    {
        $data = new ImageData();

        // LZW min. code size
        $char = $this->getNextByte();
        $size = (int) unpack('C', $char)[1];
        $data->setLzwMinCodeSize($size);

        // block size
        $char = $this->getNextByte();
        $size = (int) unpack('C', $char)[1];

        // decode data block
        $data->setData($this->getNextBytes($size));

        // terminator
        $this->getNextByte();

        return $data;
    }
}
