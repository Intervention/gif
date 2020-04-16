<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\LogicalScreen;

class LogicalScreenEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param LogicalScreen $source
     */
    public function __construct(LogicalScreen $source)
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
        $encoded = $this->source->getDescriptor()->encode();

        if ($this->source->hasColorTable()) {
            $encoded .= $this->source->getColorTable()->encode();
        }

        return $encoded;
    }
}
