<?php

namespace Intervention\Gif;

class ImageData extends AbstractEntity
{
    const LZWMIN = "\x02";
    
    /**
     * Data blocks
     *
     * @var array
     */
    protected $blocks = [];

    /**
     * Get current data blocks
     *
     * @return array
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * Set data blocks
     *
     * @param array $blocks
     */
    public function setBlocks(array $blocks): self
    {
        $this->blocks = $blocks;

        return $this;
    }

    /**
     * Add data block
     *
     * @param  string $block
     * @return self
     */
    public function addBlock(string $block): self
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Determine if any blocks are present
     *
     * @return boolean
     */
    public function hasBlocks(): bool
    {
        return count($this->getBlocks()) > 0;
    }
}
