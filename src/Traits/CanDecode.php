<?php

namespace Intervention\Gif\Traits;

use Exception;
use Intervention\Gif\Decoder\AbstractDecoder;
use Intervention\Gif\AbstractEntity;

trait CanDecode
{
    public function decode(string $source): AbstractEntity
    {
        return $this->getDecoder($source)->decode();
    }

    protected function getDecoder(string $source): AbstractDecoder
    {
        $classname = $this->getDecoderClassname();

        if (!class_exists($classname)) {
            throw new Exception("Decoder for '".get_class($this)."' not found.");
        }

        return new $classname($source);
    }

    protected function getDecoderClassname(): string
    {
        return sprintf('Intervention\Gif\Decoder\%s', $this->getShortClassname());
    }
}
