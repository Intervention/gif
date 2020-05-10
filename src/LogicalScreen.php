<?php

namespace Intervention\Gif;

class LogicalScreen extends AbstractEntity
{
    /**
     * Logical Screen Descriptor
     *
     * @var LogicalScreenDescriptor
     */
    protected $descriptor;

    /**
     * Global Color Table
     *
     * @var null|ColorTable
     */
    protected $colorTable;

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->descriptor = new LogicalScreenDescriptor();
    }

    /**
     * Get descriptor
     *
     * @return LogicalScreenDescriptor
     */
    public function getDescriptor(): ?LogicalScreenDescriptor
    {
        return $this->descriptor;
    }

    /**
     * Set descriptor
     *
     * @param LogicalScreenDescriptor $descriptor
     */
    public function setDescriptor(LogicalScreenDescriptor $descriptor): self
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
     * Determine if global color table is set
     *
     * @return boolean
     */
    public function hasColorTable()
    {
        return ! is_null($this->colorTable);
    }
}
