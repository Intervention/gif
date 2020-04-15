<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\AbstractExtension;
use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\ColorTable;
use Intervention\Gif\Contracts\DataBlock;
use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\GifDataStream as GifDataStreamObject;
use Intervention\Gif\Header;
use Intervention\Gif\LogicalScreen;
use Intervention\Gif\LogicalScreenDescriptor;
use Intervention\Gif\Trailer;

class GifDataStream extends AbstractDecoder
{
    /**
     * Decode current source to GifDataStream
     *
     * @return GifDataStreamObject
     */
    public function decode(): GifDataStreamObject
    {
        $gif = new GifDataStreamObject;

        $gif->setHeader(Header::decode($this->getNextBytes(6)));
        $gif->setLogicalScreen($this->decodeLogicalScreen());
        while (! feof($this->handle)) {
            $gif->addData($this->decodeNextDataBlock());
        }

        return $gif;
    }

    /**
     * Decode logical screen
     *
     * @return LogicalScreen
     */
    protected function decodeLogicalScreen(): LogicalScreen
    {
        $screen = new LogicalScreen;
        $screen->setDescriptor(LogicalScreenDescriptor::decode($this->getNextBytes(7)));

        if ($screen->getDescriptor()->hasGlobalColorTable()) {
            $size = $screen->getDescriptor()->getGlobalColorTableByteSize();
            $screen->setColorTable(ColorTable::decode($this->getNextBytes($size)));
        }

        return $screen;
    }

    /**
     * Decode data blocks from source into the given data stream object
     *
     * @return array
     */
    protected function decodeNextDataBlock(): DataBlock
    {
        $indicator = $this->getNextBytes(1);

        return new ApplicationExtension;
    }
}
