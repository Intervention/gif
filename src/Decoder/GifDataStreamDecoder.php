<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\AbstractExtension;
use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\ColorTable;
use Intervention\Gif\CommentExtension;
use Intervention\Gif\Contracts\DataBlock;
use Intervention\Gif\Contracts\GraphicRenderingBlock;
use Intervention\Gif\Contracts\SpecialPurposeBlock;
use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\GraphicBlock;
use Intervention\Gif\GraphicControlExtension;
use Intervention\Gif\Header;
use Intervention\Gif\ImageDescriptor;
use Intervention\Gif\LogicalScreen;
use Intervention\Gif\LogicalScreenDescriptor;
use Intervention\Gif\PlainTextExtension;
use Intervention\Gif\Trailer;

class GifDataStreamDecoder extends AbstractDecoder
{
    /**
     * Decode current source to GifDataStream
     *
     * @return AbstractEntity
     */
    public function decode(): AbstractEntity
    {
        $gif = new GifDataStream();

        $gif->setHeader(Header::decode($this->handle));
        $gif->setLogicalScreen(LogicalScreen::decode($this->handle));
        while (! feof($this->handle)) {
            if ($block = $this->decodeNextDataBlock()) {
                $gif->addData($block);
            }
        }

        return $gif;
    }

    /**
     * Decode data blocks from source into the given data stream object
     *
     * @return DataBlock
     */
    protected function decodeNextDataBlock(): ?DataBlock
    {
        //graphicblock ([GraphicControlExtension] | ((ImageDescriptor [LocalColorTable] ImageData) |  PlainTextExtension))
        //or special purpose block (ApplicationExtension | CommentExtension)

        $marker = $this->getNextByte();
        $label = $this->getNextByte();

        if ($marker === AbstractExtension::MARKER) {
            // extension
            if ($label === ApplicationExtension::LABEL) {
                // special purpose block
                return ApplicationExtension::decode($this->handle, function ($decoder) {
                    $decoder->movePointer(-2);
                });
            } elseif ($label === CommentExtension::LABEL) {
                // special purpose block
                return CommentExtension::decode($this->handle, function ($decoder) {
                    $decoder->movePointer(-2);
                });
            }
        }

        if ($marker === Trailer::MARKER) {
            return null;
        }
        
        return GraphicBlock::decode($this->handle, function ($decoder) {
            $decoder->movePointer(-2);
        });
    }
}
