<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\Header as Header;

class HeaderEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param Header $source
     */
    public function __construct(Header $source)
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
        return Header::SIGNATURE . $this->source->getVersion();
    }
}
