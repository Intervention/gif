<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\SpecialPurposeBlock;
use Intervention\Gif\DataSubBlock;

class ApplicationExtension extends AbstractExtension implements SpecialPurposeBlock
{
    public const LABEL = "\xFF";

    /**
     * Application Identifier & Auth Code
     *
     * @var string
     */
    protected $application = '';

    /**
     * Data Sub Blocks
     *
     * @var array
     */
    protected $blocks = [];

    public function getBlockSize(): int
    {
        return strlen($this->application);
    }

    public function setApplication(string $value): self
    {
        $this->application = $value;

        return $this;
    }

    public function getApplication(): string
    {
        return $this->application;
    }

    public function addBlock(DataSubBlock $block): self
    {
        $this->blocks[] = $block;

        return $this;
    }

    public function setBlocks(array $blocks): self
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }
}
