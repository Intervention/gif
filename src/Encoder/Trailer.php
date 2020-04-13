<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\Trailer as TrailerObject;

class Trailer extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param TrailerObject $source
     */
    public function __construct(TrailerObject $source)
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
        return TrailerObject::MARKER;
    }
}
