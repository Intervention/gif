<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\ImageData;

class ImageDataEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param ImageData $source
     */
    public function __construct(ImageData $source)
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
