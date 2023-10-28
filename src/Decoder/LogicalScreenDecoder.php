<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\ColorTable;
use Intervention\Gif\LogicalScreen;
use Intervention\Gif\LogicalScreenDescriptor;

class LogicalScreenDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return LogicalScreen
     */
    public function decode(): LogicalScreen
    {
        $screen = new LogicalScreen();
        $screen->setDescriptor(LogicalScreenDescriptor::decode($this->handle));
        if ($screen->getDescriptor()->hasGlobalColorTable()) {
            $screen->setColorTable(ColorTable::decode($this->handle, function ($decoder) use ($screen) {
                $decoder->setLength($screen->getDescriptor()->getGlobalColorTableByteSize());
            }));
        }

        return $screen;
    }
}
