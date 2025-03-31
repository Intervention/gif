<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

abstract class AbstractEncoder
{
    /**
     * Encode current source
     *
     * @return string
     */
    abstract public function encode(): string;

    /**
     * Create new instance
     *
     * @param mixed $source
     */
    public function __construct(protected mixed $source)
    {
        //
    }
}
