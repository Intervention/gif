<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

use Intervention\Gif\Blocks\GraphicControlExtension;
use Intervention\Gif\DisposalMethod;
use Intervention\Gif\Exceptions\DecoderException;

class GraphicControlExtensionDecoder extends AbstractPackedBitDecoder
{
    /**
     * Decode given string to current instance
     */
    public function decode(): GraphicControlExtension
    {
        $result = new GraphicControlExtension();

        // bytes 1-3
        $this->getNextBytesOrFail(3); // skip marker, label & bytesize

        // byte #4
        $packedField = $this->getNextByteOrFail();
        $result->setDisposalMethod($this->decodeDisposalMethod($packedField));
        $result->setUserInput($this->decodeUserInput($packedField));
        $result->setTransparentColorExistance($this->decodeTransparentColorExistance($packedField));

        // bytes 5-6
        $result->setDelay($this->decodeDelay($this->getNextBytesOrFail(2)));

        // byte #7
        $result->setTransparentColorIndex($this->decodeTransparentColorIndex(
            $this->getNextByteOrFail()
        ));

        // byte #8 (terminator)
        $this->getNextByteOrFail();

        return $result;
    }

    /**
     * Decode disposal method
     */
    protected function decodeDisposalMethod(string $byte): DisposalMethod
    {
        return DisposalMethod::from(
            intval(bindec($this->getPackedBits($byte, 3, 3)))
        );
    }

    /**
     * Decode user input flag
     */
    protected function decodeUserInput(string $byte): bool
    {
        return $this->hasPackedBit($byte, 6);
    }

    /**
     * Decode transparent color existance
     */
    protected function decodeTransparentColorExistance(string $byte): bool
    {
        return $this->hasPackedBit($byte, 7);
    }

    /**
     * Decode delay value
     */
    protected function decodeDelay(string $bytes): int
    {
        $unpacked = unpack('v*', $bytes);

        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new DecoderException('Failed to decode animation delay in graphic control extension');
        }

        return $unpacked[1];
    }

    /**
     * Decode transparent color index
     */
    protected function decodeTransparentColorIndex(string $byte): int
    {
        $unpacked = unpack('C', $byte);

        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new DecoderException('Failed to decode transparent color index in graphic control extension');
        }

        return $unpacked[1];
    }
}
