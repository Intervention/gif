<?php

namespace Intervention\Gif;

use Intervention\Gif\DataSubBlock;

class ImageData extends AbstractEntity
{
    /**
     * LZW min. code size
     *
     * @var int
     */
    protected $lzw_min_code_size;

    /**
     * Sub blocks
     *
     * @var array
     */
    protected $blocks = [];

    /**
     * Get LZW min. code size
     *
     * @return int
     */
    public function getLzwMinCodeSize(): int
    {
        return $this->lzw_min_code_size;
    }

    /**
     * Set lzw min. code size
     *
     * @param int $size
     * @return ImageData
     */
    public function setLzwMinCodeSize(int $size): self
    {
        $this->lzw_min_code_size = $size;

        return $this;
    }

    /**
     * Get current data sub blocks
     *
     * @return array
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * Addd sub block
     *
     * @param DataSubBlock $block
     * @return ImageData
     */
    public function addBlock(DataSubBlock $block): self
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Determine if data sub blocks are present
     *
     * @return boolean
     */
    public function hasBlocks(): bool
    {
        return count($this->blocks) >= 1;
    }
}
