<?php

namespace Intervention\Gif;

class ImageData extends AbstractEntity
{
    /**
     * LZW min. code size
     *
     * @var int
     */
    protected $lzw_min_code_size;

    /**
     * Data block
     *
     * @var string
     */
    protected $data;

    /**
     * Get LZW min. code size
     *
     * @return array
     */
    public function getLzwMinCodeSize(): int
    {
        return $this->lzw_min_code_size;
    }

    /**
     * Set lzw min. code size
     *
     * @param array $blocks
     */
    public function setLzwMinCodeSize(int $size): self
    {
        $this->lzw_min_code_size = $size;

        return $this;
    }

    /**
     * Get current data blocks
     *
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Set data block
     *
     * @param string $data
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Determine if data block is present
     *
     * @return boolean
     */
    public function hasData(): bool
    {
        return false === empty($this->getData());
    }
}
