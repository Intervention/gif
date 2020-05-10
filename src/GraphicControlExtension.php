<?php

namespace Intervention\Gif;

class GraphicControlExtension extends AbstractExtension
{
    public const LABEL = "\xF9";
    public const BLOCKSIZE = "\x04";

    /**
     * Delay time of instance
     *
     * @var integer
     */
    protected $delay = 0;

    /**
     * Disposal method of instance
     *
     * @var int
     */
    protected $disposalMethod = 0;

    /**
     * Existance flag of transparent color
     *
     * @var boolean
     */
    protected $transparentColorExistance = false;

    /**
     * Transparent color index
     *
     * @var integer
     */
    protected $transparentColorIndex = 0;

    /**
     * User input flag
     *
     * @var boolean
     */
    protected $userInput = false;

    /**
     * Set delay time (1/100 second)
     *
     * @param int $value
     */
    public function setDelay(int $value): self
    {
        $this->delay = $value;

        return $this;
    }

    /**
     * Return delay time (1/100 second)
     *
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    /**
     * Set disposal method
     *
     * @param int $method
     * @return self
     */
    public function setDisposalMethod(int $method): self
    {
        $this->disposalMethod = $method;

        return $this;
    }

    /**
     * Get disposal method
     *
     * @return int
     */
    public function getDisposalMethod(): int
    {
        return $this->disposalMethod;
    }

    /**
     * Get transparent color index
     *
     * @return int
     */
    public function getTransparentColorIndex(): int
    {
        return $this->transparentColorIndex;
    }

    /**
     * Set transparent color index
     *
     * @param int $index
     */
    public function setTransparentColorIndex(int $index): self
    {
        $this->transparentColorIndex = $index;

        return $this;
    }

    /**
     * Get current transparent color existance
     *
     * @return bool
     */
    public function getTransparentColorExistance(): bool
    {
        return $this->transparentColorExistance;
    }

    /**
     * Set existance flag of transparent color
     *
     * @param boolean $existance
     */
    public function setTransparentColorExistance(bool $existance = true): self
    {
        $this->transparentColorExistance = $existance;

        return $this;
    }

    /**
     * Get user input flag
     *
     * @return bool
     */
    public function getUserInput(): bool
    {
        return $this->userInput;
    }

    /**
     * Set user input flag
     *
     * @param bool $value
     */
    public function setUserInput(bool $value = true): self
    {
        $this->userInput = $value;

        return $this;
    }
}
