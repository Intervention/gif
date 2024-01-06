<?php

namespace Intervention\Gif\Decoders;

use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Blocks\FrameBlock;
use Intervention\Gif\Blocks\Header;
use Intervention\Gif\Blocks\LogicalScreenDescriptor;
use Intervention\Gif\Blocks\Trailer;
use Intervention\Gif\GifDataStream;

class GifDataStreamDecoder extends AbstractDecoder
{
    /**
     * Decode current source to GifDataStream
     *
     * @return GifDataStream
     */
    public function decode(): GifDataStream
    {
        $gif = new GifDataStream();

        $gif->setHeader(
            Header::decode($this->handle),
        );

        $gif->setLogicalScreenDescriptor(
            LogicalScreenDescriptor::decode($this->handle),
        );

        if ($gif->getLogicalScreenDescriptor()->hasGlobalColorTable()) {
            $length = $gif->getLogicalScreenDescriptor()->getGlobalColorTableByteSize();
            $gif->setGlobalColorTable(
                ColorTable::decode($this->handle, $length)
            );
        }

        while ($this->viewNextByte() != Trailer::MARKER) {
            $gif->addFrame(FrameBlock::decode($this->handle));
        }

        return $gif;
    }
}
