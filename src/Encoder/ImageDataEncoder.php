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
        if (! $this->source->hasBlocks()) {
            throw new EncoderException("No data blocks in ImageData.");
        }

        return implode('', [
            pack('C', $this->source->getLzwMinCodeSize()),
            implode('', array_map(function ($block) {
                return $block->encode();
            }, $this->source->getBlocks())),
            AbstractEntity::TERMINATOR,
        ]);
    }
}
