<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\Blocks\DataSubBlock;
use Intervention\Gif\Blocks\ImageData;
use Intervention\Gif\Exceptions\DecoderException;

class ImageDataDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     */
    public function decode(): ImageData
    {
        $data = new ImageData();

        // LZW min. code size
        $char = $this->getNextByteOrFail();
        $unpacked = unpack('C', $char);
        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new DecoderException('Failed to decode lzw min. code size in image data');
        }

        $data->setLzwMinCodeSize(intval($unpacked[1]));

        do {
            // decode sub blocks
            $char = $this->getNextByteOrFail();
            $unpacked = unpack('C', $char);
            if ($unpacked === false || !array_key_exists(1, $unpacked)) {
                throw new DecoderException('Failed to decode image data sub block in image data');
            }

            $size = intval($unpacked[1]);

            if ($size > 0) {
                $data->addBlock(new DataSubBlock($this->getNextBytesOrFail($size)));
            }
        } while ($char !== AbstractEntity::TERMINATOR);

        return $data;
    }
}
