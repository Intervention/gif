<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

abstract class AbstractPackedBitDecoder extends AbstractDecoder
{
    /**
     * Decode packed byte
     */
    protected function decodePackedByte(string $byte): int
    {
        return unpack('C', $byte)[1];
    }

    /**
     * Determine if packed bit is set
     */
    protected function hasPackedBit(string $byte, int $num): bool
    {
        return (bool) $this->getPackedBits($byte)[$num];
    }

    /**
     * Get packed bits
     */
    protected function getPackedBits(string $byte, int $start = 0, int $length = 8): string
    {
        $bits = str_pad(decbin($this->decodePackedByte($byte)), 8, '0', STR_PAD_LEFT);

        return substr($bits, $start, $length);
    }
}
