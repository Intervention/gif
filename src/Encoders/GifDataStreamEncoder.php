<?php

namespace Intervention\Gif\Encoders;

use Intervention\Gif\GifDataStream;

class GifDataStreamEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param GifDataStream $source
     */
    public function __construct(GifDataStream $source)
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
            $this->source->getLogicalScreenDescriptor()->encode(),
            $this->maybeEncodeGlobalColorTable(),
            $this->encodeFrames(),
            $this->source->getTrailer()->encode(),
        ]);
    }

    protected function maybeEncodeGlobalColorTable(): string
    {
        if (!$this->source->hasGlobalColorTable()) {
            return '';
        }

        return $this->source->getGlobalColorTable()->encode();
    }

    /**
     * Encode data blocks of source
     *
     * @return string
     */
    protected function encodeFrames(): string
    {
        return implode('', array_map(function ($frame) {
            return $frame->encode();
        }, $this->source->getFrames()));
    }
}
