<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\ImageData as ImageDataObject;

class ImageData extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param ImageDataObject $source
     */
    public function __construct(ImageDataObject $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     *
     * @return string
     */
    public function encode(): string
    {
        return $this->source->getData();
    }
}
