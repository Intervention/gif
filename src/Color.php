<?php

namespace Intervention\Gif;

class Color extends AbstractEntity
{
    /**
     * Red value
     *
     * @var int
     */
    protected $r;

    /**
     * Green value
     *
     * @var int
     */
    protected $g;

    /**
     * Blue value
     *
     * @var int
     */
    protected $b;

    /**
     * Create new instance
     *
     * @param int $r
     * @param int $g
     * @param int $b
     */
    public function __construct(int $r = 0, int $g = 0, int $b = 0)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    /**
     * Get red value
     *
     * @return int
     */
    public function getRed()
    {
        return $this->r;
    }

    /**
     * Set red value
     *
     * @param int $value
     */
    public function setRed(int $value): self
    {
        $this->r = $value;

        return $this;
    }

    /**
     * Get green value
     *
     * @return int
     */
    public function getGreen()
    {
        return $this->g;
    }

    /**
     * Set green value
     *
     * @param int $value
     */
    public function setGreen(int $value): self
    {
        $this->g = $value;

        return $this;
    }

    /**
     * Get blue value
     *
     * @return int
     */
    public function getBlue()
    {
        return $this->b;
    }

    /**
     * Set blue value
     *
     * @param int $value
     */
    public function setBlue(int $value): self
    {
        $this->b = $value;

        return $this;
    }

    /**
     * Return hash value of current color
     *
     * @return string
     */
    public function getHash(): string
    {
        return md5($this->r . $this->g . $this->b);
    }
}
