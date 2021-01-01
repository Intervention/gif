<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\Exception\EncoderException;
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
        if (! $this->source->hasData()) {
            throw new EncoderException("No data block in ImageData.");
        }

        return implode('', [
            pack('C', $this->source->getLzwMinCodeSize()),
            pack('C', strlen($this->source->getData())) . $this->source->getData(),
            AbstractEntity::TERMINATOR,
        ]);
    }
}
