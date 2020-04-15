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
        $extension = new ImageData;
        $extension->setData($this->source);

        return $extension;
    }
}
