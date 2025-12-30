<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

use Intervention\Gif\Blocks\ImageDescriptor;

class ImageDescriptorEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     */
    public function __construct(ImageDescriptor $source)
    {
        $this->source = $source;
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
        return pack('v*', $this->source->left());
    }

    /**
     * Encode top value
     */
    protected function encodeTop(): string
    {
        return pack('v*', $this->source->top());
    }

    /**
     * Encode width value
     */
    protected function encodeWidth(): string
    {
        return pack('v*', $this->source->width());
    }

    /**
     * Encode height value
     */
    protected function encodeHeight(): string
    {
        return pack('v*', $this->source->height());
    }

    /**
     * Encode size of local color table
     */
    protected function encodeLocalColorTableSize(): string
    {
        return str_pad(decbin($this->source->localColorTableSize()), 3, '0', STR_PAD_LEFT);
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
            (int) $this->source->localColorTableExistance(),
            (int) $this->source->isInterlaced(),
            (int) $this->source->localColorTableSorted(),
            $this->encodeReservedField(),
            $this->encodeLocalColorTableSize(),
        ])));
    }
}
