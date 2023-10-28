<?php

namespace Intervention\Gif\Encoder;

abstract class AbstractEncoder
{
    /**
     * Source to encode
     *
     * @var mixed
     */
    protected $source;

    /**
     * Encode current source
     *
     * @return string
     */
    abstract public function encode(): string;

    /**
     * Create new instance
     *
     * @param mixed  $source
     */
    public function __construct($source)
    {
        $this->source = $source;
    }
}
