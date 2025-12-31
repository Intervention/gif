<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

use Intervention\Gif\Blocks\LogicalScreenDescriptor;

class LogicalScreenDescriptorEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     */
    public function __construct(LogicalScreenDescriptor $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Encode current source
     */
    public function encode(): string
    {
        return implode('', [
            $this->encodeWidth(),
            $this->encodeHeight(),
            $this->encodePackedField(),
            $this->encodeBackgroundColorIndex(),
            $this->encodePixelAspectRatio(),
        ]);
    }

    /**
     * Encode width of current instance
     */
    protected function encodeWidth(): string
    {
        return pack('v*', $this->entity->width());
    }

    /**
     * Encode height of current instance
     */
    protected function encodeHeight(): string
    {
        return pack('v*', $this->entity->height());
    }

    /**
     * Encode background color index of global color table
     */
    protected function encodeBackgroundColorIndex(): string
    {
        return pack('C', $this->entity->backgroundColorIndex());
    }

    /**
     * Encode pixel aspect ratio
     */
    protected function encodePixelAspectRatio(): string
    {
        return pack('C', $this->entity->pixelAspectRatio());
    }

    /**
     * Return color resolution for encoding
     */
    protected function encodeColorResolution(): string
    {
        return str_pad(decbin($this->entity->bitsPerPixel() - 1), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Encode size of global color table
     */
    protected function encodeGlobalColorTableSize(): string
    {
        return str_pad(decbin($this->entity->globalColorTableSize()), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Encode packed field of current instance
     */
    protected function encodePackedField(): string
    {
        return pack('C', bindec(implode('', [
            (int) $this->entity->globalColorTableExistance(),
            $this->encodeColorResolution(),
            (int) $this->entity->globalColorTableSorted(),
            $this->encodeGlobalColorTableSize(),
        ])));
    }
}
