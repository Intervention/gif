<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Blocks\ImageData;
use Intervention\Gif\Blocks\ImageDescriptor;
use Intervention\Gif\Blocks\TableBasedImage;

class TableBasedImageDecoder extends AbstractDecoder
{
    /**
     * Decode TableBasedImage
     */
    public function decode(): TableBasedImage
    {
        $block = new TableBasedImage();

        $block->setImageDescriptor(ImageDescriptor::decode($this->filePointer));

        if ($block->imageDescriptor()->hasLocalColorTable()) {
            $block->setColorTable(
                ColorTable::decode(
                    $this->filePointer,
                    $block->imageDescriptor()->localColorTableByteSize()
                )
            );
        }

        $block->setImageData(
            ImageData::decode($this->filePointer)
        );

        return $block;
    }
}
