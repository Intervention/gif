<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\GraphicRenderingBlock;

class PlainTextExtension extends AbstractExtension implements GraphicRenderingBlock
{
    const LABEL = "\x01";

    /**
     * Array of text
     *
     * @var array
     */
    protected $text = [];

    /**
     * Get current text
     *
     * @return array
     */
    public function getText(): array
    {
        return $this->text;
    }

    /**
     * Add text
     *
     * @param string $text
     */
    public function addText(string $text): self
    {
        $this->text[] = $text;
        
        return $this;
    }

    /**
     * Set data of extension
     *
     * @param array $data
     */
    public function setText(array $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Determine if any text is present
     *
     * @return boolean
     */
    public function hasText(): bool
    {
        return count($this->text) > 0;
    }
}
