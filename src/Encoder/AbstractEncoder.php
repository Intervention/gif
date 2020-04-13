<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\GifDataStream;

abstract class AbstractEncoder
{
    /**
     * Source to encode
     *
     * @var GifDataStream
     */
    protected $source;

    /**
     * Encode current source
     *
     * @return string
     */
    abstract public function encode(): string;

    /**
     * Set source to encode
     *
     * @param GifDataStream $source
     */
    public function setSource(GifDataStream $source): self
    {
        $this->source = $source;

        return $this;
    }
}
