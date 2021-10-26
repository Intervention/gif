<?php

namespace Intervention\Gif;

use Intervention\Gif\Exception\FormatException;

class DataSubBlock extends AbstractEntity
{
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;

        if ($this->getSize() > 255) {
            throw new FormatException(
                'Data Sub-Block can not have a block size larger than 255 bytes.'
            );
        }
    }

    public function getSize(): int
    {
        return strlen($this->value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
