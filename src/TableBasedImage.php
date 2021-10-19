<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\GraphicRenderingBlock;

class TableBasedImage extends AbstractEntity implements GraphicRenderingBlock
{
    /**
     * Descriptor
     *
     * @var ImageDescriptor
     */
    protected $descriptor;

    /**
     * Local color table
     *
     * @var ColorTable
     */
    protected $colorTable;

    /**
     * Image data
     *
     * @var ImageData
     */
    protected $data;

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->descriptor = new ImageDescriptor();
        $this->data = new ImageData();
    }

    /**
     * Get descriptor
     *
     * @return ImageDescriptor
     */
    public function getDescriptor(): ImageDescriptor
    {
        return $this->descriptor;
    }

    /**
     * Set descriptor
     *
     * @param ImageDescriptor $descriptor
     */
    public function setDescriptor(ImageDescriptor $descriptor): self
    {
        $this->descriptor = $descriptor;

        return $this;
    }

    /**
     * Get color table
     *
     * @return ColorTable
     */
    public function getColorTable(): ?ColorTable
    {
        return $this->colorTable;
    }

    /**
     * Set global color table
     *
     * @param ColorTable $table
     */
    public function setColorTable(ColorTable $table): self
    {
        $this->colorTable = $table;

        return $this;
    }

    /**
     * Determine if current instance has color table
     *
     * @return boolean
     */
    public function hasColorTable()
    {
        return is_a($this->colorTable, ColorTable::class);
    }

    /**
     * Get image data
     *
     * @return ImageData
     */
    public function getData(): ImageData
    {
        return $this->data;
    }

    /**
     * Set image data
     *
     * @param ImageData $data
     */
    public function setData(ImageData $data): self
    {
        $this->data = $data;

        return $this;
    }
}
