<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

use Intervention\Gif\Blocks\Color;

class ColorEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     */
    public function __construct(Color $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Encode current source
     */
    public function encode(): string
    {
        return implode('', [
            $this->encodeColorValue($this->entity->red()),
            $this->encodeColorValue($this->entity->green()),
            $this->encodeColorValue($this->entity->blue()),
        ]);
    }

    /**
     * Encode color value
     */
    protected function encodeColorValue(int $value): string
    {
        return pack('C', $value);
    }
}
