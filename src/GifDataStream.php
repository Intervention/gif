<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\DataBlock;
use Intervention\Gif\GraphicBlock;
use Intervention\Gif\NetscapeApplicationExtension;
use Intervention\Gif\TableBasedImage;

class GifDataStream extends AbstractEntity
{
    /**
     * File header
     *
     * @var Header
     */
    protected $header;

    /**
     * Logical Screen
     *
     * @var LogicalScreen
     */
    protected $logicalScreen;

    /**
     * Array of data blocks
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->header = new Header();
        $this->logicalScreen = new LogicalScreen();
    }

    /**
     * Get header
     *
     * @return Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * Set header
     *
     * @param Header $header
     */
    public function setHeader(Header $header): self
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get logical screen
     *
     * @return LogicalScreen
     */
    public function getLogicalScreen(): LogicalScreen
    {
        return $this->logicalScreen;
    }

    /**
     * Set logical screen
     *
     * @param LogicalScreen $screen
     */
    public function setLogicalScreen(LogicalScreen $screen): self
    {
        $this->logicalScreen = $screen;

        return $this;
    }

    /**
     * Get main graphic control extension
     *
     * @return NetscapeApplicationExtension
     */
    public function getMainApplicationExtension(): ?NetscapeApplicationExtension
    {
        foreach ($this->getData() as $block) {
            if (is_a($block, NetscapeApplicationExtension::class)) {
                return $block;
            }
        }

        return null;
    }

    /**
     * Get all graphic blocks
     *
     * @return array
     */
    public function getGraphicBlocks(): array
    {
        return array_values(array_filter($this->getData(), function ($block) {
            return is_a($block, GraphicBlock::class);
        }));
    }

    /**
     * Get all table based images
     *
     * @return array
     */
    public function getTableBasedImages(): array
    {
        $blocks = array_filter($this->getGraphicBlocks(), function ($block) {
            return is_a($block->getGraphicRenderingBlock(), TableBasedImage::class);
        });

        return array_values(array_map(function ($block) {
            return $block->getGraphicRenderingBlock();
        }, $blocks));
    }

    /**
     * Get array of all image descriptors
     *
     * @return array
     */
    public function getImageDescriptors(): array
    {
        return array_values(array_map(function ($tbi) {
            return $tbi->getDescriptor();
        }, $this->getTableBasedImages()));
    }

    /**
     * Get array of data blocks
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Add to data
     *
     * @param DataBlock $block
     */
    public function addData(DataBlock $block): self
    {
        $this->data[] = $block;

        return $this;
    }

    /**
     * Set the current data
     *
     * @param array $data
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get trailer
     *
     * @return Trailer
     */
    public function getTrailer(): Trailer
    {
        return new Trailer();
    }

    /**
     * Determine if gif is animated
     *
     * @return boolean
     */
    public function isAnimated(): bool
    {
        return count($this->getGraphicBlocks()) > 1;
    }
}
