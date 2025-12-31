<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

use Intervention\Gif\Blocks\CommentExtension;
use Intervention\Gif\Blocks\FrameBlock;
use Intervention\Gif\Exceptions\EncoderException;
use Intervention\Gif\GifDataStream;

class GifDataStreamEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     */
    public function __construct(GifDataStream $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Encode current source
     *
     * @throws EncoderException
     */
    public function encode(): string
    {
        return implode('', [
            $this->entity->header()->encode(),
            $this->entity->logicalScreenDescriptor()->encode(),
            $this->maybeEncodeGlobalColorTable(),
            $this->encodeFrames(),
            $this->encodeComments(),
            $this->entity->trailer()->encode(),
        ]);
    }

    protected function maybeEncodeGlobalColorTable(): string
    {
        if (!$this->entity->hasGlobalColorTable()) {
            return '';
        }

        return $this->entity->globalColorTable()->encode();
    }

    /**
     * Encode data blocks of source
     *
     * @throws EncoderException
     */
    protected function encodeFrames(): string
    {
        return implode('', array_map(
            fn(FrameBlock $frame): string => $frame->encode(),
            $this->entity->frames(),
        ));
    }

    /**
     * Encode comment extension blocks of source
     *
     * @throws EncoderException
     */
    protected function encodeComments(): string
    {
        return implode('', array_map(
            fn(CommentExtension $commentExtension): string => $commentExtension->encode(),
            $this->entity->comments()
        ));
    }
}
