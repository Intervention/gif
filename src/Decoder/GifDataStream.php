<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\ColorTable;
use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\GifDataStream as GifDataStreamObject;
use Intervention\Gif\Header;
use Intervention\Gif\LogicalScreen;
use Intervention\Gif\LogicalScreenDescriptor;

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

        $gif->setHeader((new Header)->decode($this->getNextBytes(6)));
        $gif->setLogicalScreen($this->decodeLogicalScreen());

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
        $screen->setDescriptor((new LogicalScreenDescriptor)->decode($this->getNextBytes(7)));

        if ($screen->getDescriptor()->hasGlobalColorTable()) {
            $size = $screen->getDescriptor()->getGlobalColorTableByteSize();
            $screen->setColorTable((new ColorTable)->decode($this->getNextBytes($size)));
        }

        return $screen;
    }
}
