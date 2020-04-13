<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\Header as HeaderObject;

class Header extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param HeaderObject $source
     */
    public function __construct(HeaderObject $source)
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
        return HeaderObject::SIGNATURE.$this->source->getVersion();
    }
}
