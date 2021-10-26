<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\DataSubBlock;
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

        do {
            // decode sub blocks
            $char = $this->getNextByte();
            $size = (int) unpack('C', $char)[1];
            if ($size > 0) {
                $data->addBlock(new DataSubBlock($this->getNextBytes($size)));
            }
        } while ($char !== AbstractEntity::TERMINATOR);

        return $data;
    }
}
