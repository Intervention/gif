<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\GifDataStream as GifDataStreamObject;

class GifDataStream extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param GifDataStreamObject $source
     */
    public function __construct(GifDataStreamObject $source)
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
        return implode('', [
            $this->source->getHeader()->encode(),
            $this->source->getLogicalScreen()->encode(),
            $this->encodeData(),
            $this->source->getTrailer()->encode(),
        ]);
    }

    /**
     * Encode data blocks of source
     *
     * @return string
     */
    protected function encodeData(): string
    {
        return implode('', array_map(function ($block) {
            return $block->encode();
        }, $this->source->getData()));
    }
}
