<?php

namespace Intervention\Gif\Decoder;

abstract class PackedBitDecoder extends AbstractDecoder
{
    /**
     * Decode packed field
     *
     * @return int
     */
    abstract protected function decodePackedField(): int;

    /**
     * Determine if packed bit is set
     *
     * @param  int  $num from left to right, starting with 0
     * @return boolean
     */
    protected function hasPackedBit(int $num): bool
    {
        return (bool) $this->getPackedBits()[$num];
    }

    /**
     * Get packed bits
     *
     * @param  integer $start
     * @param  integer $length
     * @return string
     */
    protected function getPackedBits($start = 0, $length = 8): string
    {
        $bits = str_pad(decbin($this->decodePackedField($this->source)), 8, 0, STR_PAD_LEFT);

        return substr($bits, $start, $length);
    }
}
