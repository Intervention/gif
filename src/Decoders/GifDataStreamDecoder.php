<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

use Intervention\Gif\AbstractExtension;
use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Blocks\CommentExtension;
use Intervention\Gif\Blocks\FrameBlock;
use Intervention\Gif\Blocks\Header;
use Intervention\Gif\Blocks\LogicalScreenDescriptor;
use Intervention\Gif\Blocks\Trailer;
use Intervention\Gif\Exceptions\DecoderException;
use Intervention\Gif\GifDataStream;

class GifDataStreamDecoder extends AbstractDecoder
{
    /**
     * Decode current source to GifDataStream.
     *
     * @throws DecoderException
     */
    public function decode(): GifDataStream
    {
        $gif = new GifDataStream();

        $gif->setHeader(
            Header::decode($this->filePointer),
        );

        $gif->setLogicalScreenDescriptor(
            LogicalScreenDescriptor::decode($this->filePointer),
        );

        if ($gif->logicalScreenDescriptor()->hasGlobalColorTable()) {
            $length = $gif->logicalScreenDescriptor()->globalColorTableByteSize();
            $gif->setGlobalColorTable(
                ColorTable::decode($this->filePointer, $length)
            );
        }

        while ($this->viewNextByteOrFail() !== Trailer::MARKER) {
            match ($this->viewNextBytesOrFail(2)) {
                // handle trailing "global" comment blocks which are not part of "FrameBlock"
                AbstractExtension::MARKER . CommentExtension::LABEL
                => $gif->addComment(
                    CommentExtension::decode($this->filePointer)
                ),
                default => $gif->addFrame(
                    FrameBlock::decode($this->filePointer)
                ),
            };
        }

        return $gif;
    }
}
