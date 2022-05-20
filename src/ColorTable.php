<?php

namespace Intervention\Gif;

class ColorTable extends AbstractEntity
{
    /**
     * Array of colors in table
     *
     * @var array
     */
    protected $colors = [];

    /**
     * Create new instance
     *
     * @param array $colors
     */
    public function __construct(array $colors = [])
    {
        $this->setColors($colors);
    }

    /**
     * Return array of current colors
     *
     * @return array
     */
    public function getColors(): array
    {
        return array_values($this->colors);
    }

    /**
     * Add color to table
     *
     * @param int $r
     * @param int $g
     * @param int $b
     */
    public function addRgb(int $r, int $g, int $b): self
    {
        $this->addColor(new Color($r, $g, $b));

        return $this;
    }

    /**
     * Add color to table
     *
     * @param Color $color
     */
    public function addColor(Color $color): self
    {
        $this->colors[] = $color;

        return $this;
    }

    /**
     * Reset colors to array of color objects
     *
     * @param array $colors
     */
    public function setColors(array $colors): self
    {
        $this->empty();
        foreach ($colors as $color) {
            $this->addColor($color);
        }

        return $this;
    }

    /**
     * Count colors of current instance
     *
     * @return int
     */
    public function countColors(): int
    {
        return count($this->colors);
    }

    /**
     * Determine if any colors are present on the current table
     *
     * @return boolean
     */
    public function hasColors()
    {
        return $this->countColors() >= 1;
    }

    /**
     * Empty color table
     *
     * @return self
     */
    public function empty(): self
    {
        $this->colors = [];

        return $this;
    }

    /**
     * Get size of color table in logical screen descriptor
     *
     * @return int
     */
    public function getLogicalSize(): int
    {
        switch ($this->countColors()) {
            case 4:
                return 1;

            case 8:
                return 2;

            case 16:
                return 3;

            case 32:
                return 4;

            case 64:
                return 5;

            case 128:
                return 6;

            case 256:
                return 7;

            default:
                return 0;
        }
    }

    /**
     * Calculate the number of bytes contained by the current table
     *
     * @return int
     */
    public function getByteSize(): int
    {
        if (! $this->hasColors()) {
            return 0;
        }

        return 3 * pow(2, $this->getLogicalSize() + 1);
    }
}
