<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\GraphicBlock;
use Intervention\Gif\GraphicControlExtension;
use Intervention\Gif\PlainTextExtension;
use Intervention\Gif\TableBasedImage;

class GraphicBlockDecoder extends AbstractDecoder
{
    /**
     * Decode current sourc
     *
     * @return GraphicBlock
     */
    public function decode(): GraphicBlock
    {
        $block = new GraphicBlock();

        $this->getNextByte();
        $label = $this->getNextByte(); // label
        $back = -2;

        // plain text extension
        if ($label === PlainTextExtension::LABEL) {
            // graphic block is already complete
            return $block->setGraphicRenderingBlock(
                PlainTextExtension::decode($this->handle, function ($decoder) use ($back) {
                    $decoder->movePointer($back);
                })
            );
        }

        if ($label === GraphicControlExtension::LABEL) {
            // graphic control extension
            $block->setGraphicControlExtension(
                GraphicControlExtension::decode($this->handle, function ($decoder) use ($back) {
                    $decoder->movePointer($back);
                })
            );

            $back = 0;
        }

        // table based image
        $block->setGraphicRenderingBlock(
            TableBasedImage::decode($this->handle, function ($decoder) use ($back) {
                $decoder->movePointer($back);
            })
        );

        return $block;
    }
}
