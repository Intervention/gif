<?php

namespace Intervention\Gif;

class ImageData extends AbstractEntity
{
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

    /**
     * Append to current data
     *
     * @param  string $data
     * @return self
     */
    public function append(string $data): self
    {
        $this->data .= $data;

        return $this;
    }
}
