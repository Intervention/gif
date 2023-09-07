<?php

namespace Intervention\Gif;

class ImageDescriptor extends AbstractEntity
{
    public const SEPARATOR = "\x2C";

    /**
     * Width of frame
     *
     * @var int
     */
    protected $width;

    /**
     * Height of frame
     *
     * @var int
     */
    protected $height;

    /**
     * Left position of frame
     *
     * @var int
     */
    protected $left;

    /**
     * Top position of frame
     *
     * @var int
     */
    protected $top;

    /**
     * Determine if frame is interlaced
     *
     * @var bool
     */
    protected $interlaced = false;

    /**
     * Local color table flag
     *
     * @var bool
     */
    protected $localColorTableExistance = false;

    /**
     * Sort flag of local color table
     *
     * @var bool
     */
    protected $localColorTableSorted = false;

    /**
     * Size of local color table
     *
     * @var integer
     */
    protected $localColorTableSize = 0;

    /**
     * Get current width
     *
     * @return int
     */
    public function getWidth(): int
    {
        return intval($this->width);
    }

    /**
     * Get current width
     *
     * @return int
     */
    public function getHeight(): int
    {
        return intval($this->height);
    }

    /**
     * Get current Top
     *
     * @return int
     */
    public function getTop(): int
    {
        return intval($this->top);
    }

    /**
     * Get current Left
     *
     * @return int
     */
    public function getLeft(): int
    {
        return intval($this->left);
    }

    /**
     * Set size of current instance
     *
     * @param int $width
     * @param int $height
     */
    public function setSize(int $width, int $height): self
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * Set position of current instance
     *
     * @param int $left
     * @param int $top
     */
    public function setPosition(int $left, int $top): self
    {
        $this->left = $left;
        $this->top = $top;

        return $this;
    }

    /**
     * Determine if frame is interlaced
     *
     * @return boolean
     */
    public function isInterlaced(): bool
    {
        return $this->interlaced === true;
    }

    /**
     * Set or unset interlaced value
     *
     * @param boolean $value
     */
    public function setInterlaced(bool $value = true): self
    {
        $this->interlaced = $value;

        return $this;
    }

    /**
     * Determine if local color table is present
     *
     * @return boolean
     */
    public function getLocalColorTableExistance(): bool
    {
        return $this->localColorTableExistance;
    }

    /**
     * Alias for getLocalColorTableExistance
     *
     * @return boolean
     */
    public function hasLocalColorTable(): bool
    {
        return $this->getLocalColorTableExistance();
    }

    /**
     * Set local color table flag
     *
     * @param boolean $existance
     * @return self
     */
    public function setLocalColorTableExistance($existance = true): self
    {
        $this->localColorTableExistance = $existance;

        return $this;
    }

    /**
     * Get local color table sorted flag
     *
     * @return bool
     */
    public function getLocalColorTableSorted(): bool
    {
        return $this->localColorTableSorted;
    }

    /**
     * Set local color table sorted flag
     *
     * @param boolean $sorted
     * @return self
     */
    public function setLocalColorTableSorted($sorted = true): self
    {
        $this->localColorTableSorted = $sorted;

        return $this;
    }

    /**
     * Get size of local color table
     *
     * @return int
     */
    public function getLocalColorTableSize(): int
    {
        return $this->localColorTableSize;
    }

    /**
     * Get byte size of global color table
     *
     * @return int
     */
    public function getLocalColorTableByteSize(): int
    {
        return 3 * pow(2, $this->getLocalColorTableSize() + 1);
    }

    /**
     * Set size of local color table
     *
     * @param int $size
     */
    public function setLocalColorTableSize(int $size): self
    {
        $this->localColorTableSize = $size;

        return $this;
    }
}
