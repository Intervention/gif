<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\GraphicControlExtension as GraphicControlExtensionObject;

class GraphicControlExtension extends PackedBitDecoder
{
    /**
     * Decode given string to current instance
     *
     * @param  string $source
     * @return GraphicControlExtensionObject
     */
    public function decode(): GraphicControlExtensionObject
    {
        $result = new GraphicControlExtensionObject;

        $result->setDelay($this->decodeDelay());
        $result->setDisposalMethod($this->decodeDisposalMethod());
        $result->setTransparentColorExistance($this->decodeTransparentColorExistance());
        $result->setTransparentColorIndex($this->decodeTransparentColorIndex());
        $result->setUserInput($this->decodeUserInput());

        return $result;
    }

    /**
     * Decode packed field
     *
     * @return int
     */
    protected function decodePackedField(): int
    {
        return unpack('C', substr($this->source, 3, 1))[1];
    }

    /**
     * Decode delay value
     *
     * @return int
     */
    protected function decodeDelay(): int
    {
        return unpack('v*', substr($this->source, 4, 2))[1];
    }

    /**
     * Decode disposal method
     *
     * @return int
     */
    protected function decodeDisposalMethod(): int
    {
        return bindec($this->getPackedBits(3, 3));
    }

    /**
     * Decode transparent color existance
     *
     * @return bool
     */
    protected function decodeTransparentColorExistance(): bool
    {
        return $this->hasPackedBit(7);
    }

    /**
     * Decode transparent color index
     *
     * @return int
     */
    protected function decodeTransparentColorIndex(): int
    {
        return unpack('C', substr($this->source, 6, 1))[1];
    }

    /**
     * Decode user input flag
     *
     * @return bool
     */
    protected function decodeUserInput(): bool
    {
        return $this->hasPackedBit(6);
    }
}
