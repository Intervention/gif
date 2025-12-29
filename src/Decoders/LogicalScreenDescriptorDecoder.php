<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

use Intervention\Gif\Blocks\LogicalScreenDescriptor;
use Intervention\Gif\Exceptions\DecoderException;

class LogicalScreenDescriptorDecoder extends AbstractPackedBitDecoder
{
    /**
     * Decode given string to current instance
     */
    public function decode(): LogicalScreenDescriptor
    {
        $logicalScreenDescriptor = new LogicalScreenDescriptor();

        // bytes 1-4
        $logicalScreenDescriptor->setSize(
            $this->decodeWidth($this->getNextBytesOrFail(2)),
            $this->decodeHeight($this->getNextBytesOrFail(2))
        );

        // byte 5
        $packedField = $this->getNextByteOrFail();

        $logicalScreenDescriptor->setGlobalColorTableExistance(
            $this->decodeGlobalColorTableExistance($packedField)
        );

        $logicalScreenDescriptor->setBitsPerPixel(
            $this->decodeBitsPerPixel($packedField)
        );

        $logicalScreenDescriptor->setGlobalColorTableSorted(
            $this->decodeGlobalColorTableSorted($packedField)
        );

        $logicalScreenDescriptor->setGlobalColorTableSize(
            $this->decodeGlobalColorTableSize($packedField)
        );

        // byte 6
        $logicalScreenDescriptor->setBackgroundColorIndex(
            $this->decodeBackgroundColorIndex($this->getNextByteOrFail())
        );

        // byte 7
        $logicalScreenDescriptor->setPixelAspectRatio(
            $this->decodePixelAspectRatio($this->getNextByteOrFail())
        );

        return $logicalScreenDescriptor;
    }

    /**
     * Decode width
     */
    protected function decodeWidth(string $source): int
    {
        $unpacked = unpack('v*', $source);

        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new DecoderException('Failed to decode width in logical screen descriptor');
        }

        return $unpacked[1];
    }

    /**
     * Decode height
     */
    protected function decodeHeight(string $source): int
    {
        $unpacked = unpack('v*', $source);

        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new DecoderException('Failed to decode height in logical screen descriptor');
        }

        return $unpacked[1];
    }

    /**
     * Decode existance of global color table
     */
    protected function decodeGlobalColorTableExistance(string $byte): bool
    {
        return $this->hasPackedBit($byte, 0);
    }

    /**
     * Decode color resolution in bits per pixel
     */
    protected function decodeBitsPerPixel(string $byte): int
    {
        return intval(bindec($this->getPackedBits($byte, 1, 3))) + 1;
    }

    /**
     * Decode global color table sorted status
     */
    protected function decodeGlobalColorTableSorted(string $byte): bool
    {
        return $this->hasPackedBit($byte, 4);
    }

    /**
     * Decode size of global color table
     */
    protected function decodeGlobalColorTableSize(string $byte): int
    {
        return intval(bindec($this->getPackedBits($byte, 5, 3)));
    }

    /**
     * Decode background color index
     */
    protected function decodeBackgroundColorIndex(string $source): int
    {
        $unpacked = unpack('C', $source);

        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new DecoderException('Failed to decode background color index in logical screen descriptor');
        }

        return $unpacked[1];
    }

    /**
     * Decode pixel aspect ratio
     */
    protected function decodePixelAspectRatio(string $source): int
    {
        $unpacked = unpack('C', $source);

        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new DecoderException('Failed to decode pixel aspect ratio in logical screen descriptor');
        }

        return $unpacked[1];
    }
}
