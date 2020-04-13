<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\ImageDescriptor as ImageDescriptorObject;

class ImageDescriptor extends PackedBitDecoder
{
    /**
     * Decode given string to current instance
     *
     * @param  string $source
     * @return self
     */
    public function decode(): ImageDescriptorObject
    {
        $descriptor = new ImageDescriptorObject;

        $descriptor->setSize(
            $this->decodeWidth(),
            $this->decodeHeight()
        );

        $descriptor->setPosition(
            $this->decodeTop(),
            $this->decodeLeft()
        );

        $descriptor->setLocalColorTableExistance(
            $this->decodeLocalColorTableExistance()
        );

        $descriptor->setLocalColorTableSorted(
            $this->decodeLocalColorTableSorted()
        );

        $descriptor->setLocalColorTableSize(
            $this->decodeLocalColorTableSize()
        );

        $descriptor->setInterlaced(
            $this->decodeInterlaced()
        );

        return $descriptor;
    }

    /**
     * Decode packed field
     *
     * @return int
     */
    protected function decodePackedField(): int
    {
        return unpack('C', substr($this->source, 9, 1))[1];
    }

    /**
     * Decode width
     *
     * @return int
     */
    protected function decodeWidth(): int
    {
        return unpack('v*', substr($this->source, 5, 2))[1];
    }

    /**
     * Decode height
     *
     * @return int
     */
    protected function decodeHeight(): int
    {
        return unpack('v*', substr($this->source, 7, 2))[1];
    }

    /**
     * Decode top position
     *
     * @return int
     */
    protected function decodeTop(): int
    {
        return unpack('v*', substr($this->source, 3, 2))[1];
    }

    /**
     * Decode left position
     *
     * @return int
     */
    protected function decodeLeft(): int
    {
        return unpack('v*', substr($this->source, 1, 2))[1];
    }

    /**
     * Decode local color table existance
     *
     * @return bool
     */
    protected function decodeLocalColorTableExistance(): bool
    {
        return $this->hasPackedBit(0);
    }

    /**
     * Decode local color table sort method
     *
     * @return bool
     */
    protected function decodeLocalColorTableSorted(): bool
    {
        return $this->hasPackedBit(2);
    }

    /**
     * Decode local color table size
     *
     * @return int
     */
    protected function decodeLocalColorTableSize(): int
    {
        return bindec($this->getPackedBits(5, 3));
    }

    /**
     * Decode interlaced flag
     *
     * @return bool
     */
    protected function decodeInterlaced(): bool
    {
        return $this->hasPackedBit(1);
    }
}
