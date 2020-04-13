<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\GraphicRenderingBlock;

class PlainTextExtension extends AbstractExtension implements GraphicRenderingBlock
{
    const LABEL = "\x01";

    /**
     * Data
     *
     * @var string|null
     */
    protected $data;

    /**
     * Get current data
     *
     * @return string
     */
    public function getData(): string
    {
        return (string) $this->data;
    }

    /**
     * Set data of extension
     *
     * @param string $data
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }
}
