<?php

namespace Intervention\Gif\Contracts;

interface Entity
{
    /**
     * Encode current entity
     *
     * @return string
     */
    public function encode(): string;
}
