<?php

namespace Intervention\Gif\Encoder;

abstract class AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param mixed $source
     */
    public function __construct(protected mixed $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     *
     * @return string
     */
    abstract public function encode(): string;
}
