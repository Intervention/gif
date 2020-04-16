<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\ImageData;

class ImageDataDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return ImageData
     */
    public function decode(): ImageData
    {
        $data = new ImageData;

        // LZW min. code size
        $this->getNextByte();

        do {
            // decode sub blocks
            $char = $this->getNextByte();
            $size = (int) unpack('C', $char)[1];
            if ($size > 0) {
                $data->addBlock($this->getNextBytes($size));
            }
        } while ($char !== "\x00");

        return $data;
    }
}
