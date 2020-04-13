<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\Color as ColorObject;

class Color extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param ColorObject $source
     */
    public function __construct(ColorObject $source)
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
            $this->encodeRed(),
            $this->encodeGreen(),
            $this->encodeBlue(),
        ]);
    }

    /**
     * Encode red value
     *
     * @return string
     */
    protected function encodeRed(): string
    {
        return pack('C', $this->source->getRed());
    }

    /**
     * Encode green value
     *
     * @return string
     */
    protected function encodeGreen(): string
    {
        return pack('C', $this->source->getGreen());
    }

    /**
     * Encode blue value
     *
     * @return string
     */
    protected function encodeBlue(): string
    {
        return pack('C', $this->source->getBlue());
    }
}
