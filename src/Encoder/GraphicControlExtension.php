<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\GraphicControlExtension as GraphicControlExtensionObject;

class GraphicControlExtension extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param GraphicControlExtensionObject $source
     */
    public function __construct(GraphicControlExtensionObject $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     *
     * @return string
     */
    public function encode(): string
    {
        return implode('', [
            GraphicControlExtensionObject::MARKER,
            GraphicControlExtensionObject::LABEL,
            GraphicControlExtensionObject::BLOCKSIZE,
            $this->encodePackedField(),
            $this->encodeDelay(),
            $this->encodeTransparentColorIndex(),
            GraphicControlExtensionObject::TERMINATOR,
        ]);
    }

    /**
     * Encode delay time
     *
     * @return string
     */
    protected function encodeDelay(): string
    {
        return pack('v*', $this->source->getDelay());
    }

    /**
     * Encode transparent color index
     *
     * @return string
     */
    protected function encodeTransparentColorIndex(): string
    {
        return pack('C', $this->source->getTransparentColorIndex());
    }

    /**
     * Encode packed field
     *
     * @return string
     */
    protected function encodePackedField(): string
    {
        return pack('C', bindec(implode('', [
            str_pad(0, 3, 0, STR_PAD_LEFT),
            str_pad(decbin($this->source->getDisposalMethod()), 3, 0, STR_PAD_LEFT),
            (int) $this->source->getUserInput(),
            (int) $this->source->getTransparentColorExistance(),
        ])));
    }
}
