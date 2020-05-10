<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\ColorTable;
use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\ImageData;
use Intervention\Gif\ImageDescriptor;
use Intervention\Gif\TableBasedImage;

class TableBasedImageDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return AbstractEntity
     */
    public function decode(): AbstractEntity
    {
        $image = new TableBasedImage();

        // descriptor
        $image->setDescriptor(ImageDescriptor::decode($this->handle));

        // local color table
        if ($image->getDescriptor()->hasLocalColorTable()) {
            $image->setColortable(ColorTable::decode($this->handle, function ($decoder) use ($image) {
                $decoder->setLength($image->getDescriptor()->getLocalColorTableByteSize());
            }));
        }

        // image data
        $image->setData(ImageData::decode($this->handle));

        return $image;
    }
}
