<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\LogicalScreenDescriptor;

class LogicalScreenDescriptorDecoder extends AbstractPackedBitDecoder
{
    /**
     * Decode given string to current instance
     *
     * @return LogicalScreenDescriptor
     */
    public function decode(): LogicalScreenDescriptor
    {
        $logicalScreenDescriptor = new LogicalScreenDescriptor;

        $logicalScreenDescriptor->setSize(
            $this->decodeWidth(),
            $this->decodeHeight()
        );

        $logicalScreenDescriptor->setBackgroundColorIndex(
            $this->decodeBackgroundColorIndex()
        );

        $logicalScreenDescriptor->setPixelAspectRatio(
            $this->decodePixelAspectRatio()
        );

        $logicalScreenDescriptor->setGlobalColorTableExistance(
            $this->decodeGlobalColorTableExistance()
        );

        $logicalScreenDescriptor->setGlobalColorTableSorted(
            $this->decodeGlobalColorTableSorted()
        );

        $logicalScreenDescriptor->setBitsPerPixel(
            $this->decodeBitsPerPixel()
        );

        $logicalScreenDescriptor->setGlobalColorTableSize(
            $this->decodeGlobalColorTableSize()
        );

        return $logicalScreenDescriptor;
    }

    /**
     * Decode width
     *
     * @return int
     */
    protected function decodeWidth(): int
    {
        return unpack('v*', substr($this->source, 0, 2))[1];
    }

    /**
     * Decode height
     *
     * @return int
     */
    protected function decodeHeight(): int
    {
        return unpack('v*', substr($this->source, 2, 2))[1];
    }

    /**
     * Decode packed field
     *
     * @return int
     */
    protected function decodePackedField(): int
    {
        return unpack('C', substr($this->source, 4, 1))[1];
    }
    
    /**
     * Decode background color index
     *
     * @return int
     */
    protected function decodeBackgroundColorIndex(): int
    {
        return unpack('C', substr($this->source, 5, 1))[1];
    }

    /**
     * Decode pixel aspect ratio
     *
     * @return int
     */
    protected function decodePixelAspectRatio(): int
    {
        return unpack('C', substr($this->source, 6, 1))[1];
    }

    /**
     * Decode existance of global color table
     *
     * @return bool
     */
    protected function decodeGlobalColorTableExistance(): bool
    {
        return $this->hasPackedBit(0);
    }

    /**
     * Decode global color table sorted status
     *
     * @return bool
     */
    protected function decodeGlobalColorTableSorted(): bool
    {
        return $this->hasPackedBit(4);
    }

    /**
     * Decode color resolution in bits per pixel
     *
     * @return int
     */
    protected function decodeBitsPerPixel(): int
    {
        return bindec($this->getPackedBits(1, 3)) + 1;
    }

    /**
     * Decode size of global color table
     *
     * @return int
     */
    protected function decodeGlobalColorTableSize(): int
    {
        return bindec($this->getPackedBits(5, 3));
    }
}
