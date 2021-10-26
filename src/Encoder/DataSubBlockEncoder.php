<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\DataSubBlock;

class DataSubBlockEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param DataSubBlock $source
     */
    public function __construct(DataSubBlock $source)
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
        return pack('C', $this->source->getSize()) . $this->source->getValue();
    }
}
