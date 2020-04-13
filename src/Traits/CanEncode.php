<?php

namespace Intervention\Gif\Traits;

use Exception;

trait CanEncode
{
    public function encode(): string
    {
        return $this->getEncoder()->encode();
    }

    protected function getEncoder()
    {
        $classname = $this->getEncoderClassname();

        if (!class_exists($classname)) {
            throw new Exception("Encoder for '".get_class($this)."' not found.");
        }

        return new $classname($this);
    }

    protected function getEncoderClassname()
    {
        return sprintf('Intervention\Gif\Encoder\%s', $this->getShortClassname());
    }
}
