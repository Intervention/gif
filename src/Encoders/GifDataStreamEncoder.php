<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

use Intervention\Gif\Blocks\CommentExtension;
use Intervention\Gif\Blocks\FrameBlock;
use Intervention\Gif\GifDataStream;

class GifDataStreamEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     */
    public function __construct(GifDataStream $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     */
    public function encode(): string
    {
        return implode('', [
            $this->source->header()->encode(),
            $this->source->logicalScreenDescriptor()->encode(),
            $this->maybeEncodeGlobalColorTable(),
            $this->encodeFrames(),
            $this->encodeComments(),
            $this->source->trailer()->encode(),
        ]);
    }

    protected function maybeEncodeGlobalColorTable(): string
    {
        if (!$this->source->hasGlobalColorTable()) {
            return '';
        }

        return $this->source->globalColorTable()->encode();
    }

    /**
     * Encode data blocks of source
     */
    protected function encodeFrames(): string
    {
        return implode('', array_map(
            fn(FrameBlock $frame): string => $frame->encode(),
            $this->source->frames(),
        ));
    }

    /**
     * Encode comment extension blocks of source
     */
    protected function encodeComments(): string
    {
        return implode('', array_map(
            fn(CommentExtension $commentExtension): string => $commentExtension->encode(),
            $this->source->comments()
        ));
    }
}
