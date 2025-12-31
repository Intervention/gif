<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

use Intervention\Gif\Blocks\ImageDescriptor;

class ImageDescriptorEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     */
    public function __construct(ImageDescriptor $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Encode current source
     */
    public function encode(): string
    {
        return implode('', [
            ImageDescriptor::SEPARATOR,
            $this->encodeLeft(),
            $this->encodeTop(),
            $this->encodeWidth(),
            $this->encodeHeight(),
            $this->encodePackedField(),
        ]);
    }

    /**
     * Encode left value
     */
    protected function encodeLeft(): string
    {
        return pack('v*', $this->entity->left());
    }

    /**
     * Encode top value
     */
    protected function encodeTop(): string
    {
        return pack('v*', $this->entity->top());
    }

    /**
     * Encode width value
     */
    protected function encodeWidth(): string
    {
        return pack('v*', $this->entity->width());
    }

    /**
     * Encode height value
     */
    protected function encodeHeight(): string
    {
        return pack('v*', $this->entity->height());
    }

    /**
     * Encode size of local color table
     */
    protected function encodeLocalColorTableSize(): string
    {
        return str_pad(decbin($this->entity->localColorTableSize()), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Encode reserved field
     */
    protected function encodeReservedField(): string
    {
        return str_pad('0', 2, '0', STR_PAD_LEFT);
    }

    /**
     * Encode packed field
     */
    protected function encodePackedField(): string
    {
        return pack('C', bindec(implode('', [
            (int) $this->entity->localColorTableExistance(),
            (int) $this->entity->isInterlaced(),
            (int) $this->entity->localColorTableSorted(),
            $this->encodeReservedField(),
            $this->encodeLocalColorTableSize(),
        ])));
    }
}
