<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\AbstractEntity;

abstract class AbstractEncoder
{
    /**
     * Source to encode
     *
     * @var AbstractEntity
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
     * @param AbstractEntity  $source
     */
    public function __construct(AbstractEntity $source)
    {
        $this->source = $source;
    }
}
