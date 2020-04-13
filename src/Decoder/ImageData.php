<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\ImageData as ImageDataObject;

class ImageData extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return ImageDataObject
     */
    public function decode(): ImageDataObject
    {
        $extension = new ImageDataObject;
        $extension->setData($this->source);

        return $extension;
    }
}
