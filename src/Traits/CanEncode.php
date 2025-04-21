<?php

declare(strict_types=1);

namespace Intervention\Gif\Traits;

use Intervention\Gif\Encoders\AbstractEncoder;
use Intervention\Gif\Exceptions\EncoderException;

trait CanEncode
{
    /**
     * Encode current entity
     *
     * @throws EncoderException
     * @return string
     */
    public function encode(): string
    {
        return $this->encoder()->encode();
    }

    /**
     * Get encoder object for current entity
     *
     * @throws EncoderException
     * @return AbstractEncoder
     */
    private function encoder(): AbstractEncoder
    {
        $classname = $this->encoderClassname();

        if (!class_exists($classname)) {
            throw new EncoderException("Encoder for '" . $this::class . "' not found.");
        }

        return new $classname($this);
    }

    /**
     * Get encoder classname for current entity
     *
     * @return string
     */
    private function encoderClassname(): string
    {
        return sprintf('Intervention\Gif\Encoders\%sEncoder', $this->shortClassname());
    }
}
