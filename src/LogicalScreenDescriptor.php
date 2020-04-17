<?php

namespace Intervention\Gif;

class LogicalScreenDescriptor extends AbstractEntity
{
    /**
     * Width
     *
     * @var integer
     */
    protected $width;

    /**
     * Height
     *
     * @var integer
     */
    protected $height;

    /**
     * Global color table flag
     *
     * @var bool
     */
    protected $globalColorTableExistance = false;

    /**
     * Sort flag of global color table
     *
     * @var bool
     */
    protected $globalColorTableSorted = false;

    /**
     * Size of global color table
     *
     * @var integer
     */
    protected $globalColorTableSize = 0;

    /**
     * Background color index
     *
     * @var integer
     */
    protected $backgroundColorIndex = 0;

    /**
     * Color resolution
     *
     * @var integer
     */
    protected $bitsPerPixel = 8;

    /**
     * Pixel aspect ration
     *
     * @var integer
     */
    protected $pixelAspectRatio = 0;

    /**
     * Set size
     *
     * @param integer $width
     * @param integer $height
     */
    public function setSize(int $width, int $height): self
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * Get width of current instance
     *
     * @return int
     */
    public function getWidth(): int
    {
        return intval($this->width);
    }

    /**
     * Get height of current instance
     *
     * @return int
     */
    public function getHeight(): int
    {
        return intval($this->height);
    }

    /**
     * Determine if global color table is present
     *
     * @return boolean
     */
    public function getGlobalColorTableExistance(): bool
    {
        return $this->globalColorTableExistance;
    }

    /**
     * Alias of getGlobalColorTableExistance
     *
     * @return boolean
     */
    public function hasGlobalColorTable(): bool
    {
        return $this->getGlobalColorTableExistance();
    }

    /**
     * Set global color table flag
     *
     * @param boolean $existance
     * @return self
     */
    public function setGlobalColorTableExistance($existance = true): self
    {
        $this->globalColorTableExistance = $existance;

        return $this;
    }

    /**
     * Get global color table sorted flag
     *
     * @return bool
     */
    public function getGlobalColorTableSorted(): bool
    {
        return $this->globalColorTableSorted;
    }

    /**
     * Set global color table sorted flag
     *
     * @param boolean $sorted
     * @return self
     */
    public function setGlobalColorTableSorted($sorted = true): self
    {
        $this->globalColorTableSorted = $sorted;

        return $this;
    }

    /**
     * Get size of global color table
     *
     * @return int
     */
    public function getGlobalColorTableSize(): int
    {
        return $this->globalColorTableSize;
    }

    /**
     * Get byte size of global color table
     *
     * @return int
     */
    public function getGlobalColorTableByteSize(): int
    {
        return 3 * pow(2, $this->getGlobalColorTableSize() + 1);
    }

    /**
     * Set size of global color table
     *
     * @param int $size
     */
    public function setGlobalColorTableSize(int $size): self
    {
        $this->globalColorTableSize = $size;

        return $this;
    }

    /**
     * Get background color index
     *
     * @return int
     */
    public function getBackgroundColorIndex(): int
    {
        return $this->backgroundColorIndex;
    }

    /**
     * Set background color index
     *
     * @param int $index
     */
    public function setBackgroundColorIndex(int $index): self
    {
        $this->backgroundColorIndex = $index;

        return $this;
    }

    /**
     * Get current pixel aspect ration
     *
     * @return int
     */
    public function getPixelAspectRatio(): int
    {
        return $this->pixelAspectRatio;
    }

    /**
     * Set pixel aspect ratio
     *
     * @param int $ratio
     */
    public function setPixelAspectRatio(int $ratio): self
    {
        $this->pixelAspectRatio = $ratio;

        return $this;
    }

    /**
     * Get color resolution
     *
     * @return int
     */
    public function getBitsPerPixel()
    {
        return $this->bitsPerPixel;
    }

    /**
     * Set color resolution
     *
     * @param int $value
     */
    public function setBitsPerPixel(int $value): self
    {
        $this->bitsPerPixel = $value;

        return $this;
    }
}
