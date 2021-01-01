<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\DataBlock;
use Intervention\Gif\Contracts\GraphicRenderingBlock;

class GraphicBlock extends AbstractEntity implements DataBlock
{
    /**
     * Graphic control extension
     *
     * @var GraphicControlExtension
     */
    protected $graphicControlExtension;

    /**
     * Graphic rendering block
     *
     * @var GraphicRenderingBlock
     */
    protected $graphicRenderingBlock;

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->graphicRenderingBlock = new TableBasedImage();
    }

    /**
     * Get graphic control extension
     *
     * @return GraphicControlExtension
     */
    public function getGraphicControlExtension(): ?GraphicControlExtension
    {
        return $this->graphicControlExtension;
    }

    /**
     * Set graphic control extension
     *
     * @param GraphicControlExtension $extension
     */
    public function setGraphicControlExtension(GraphicControlExtension $extension): self
    {
        $this->graphicControlExtension = $extension;

        return $this;
    }

    /**
     * Determine if current instance has graphic control extension
     *
     * @return boolean
     */
    public function hasGraphicControlExtension()
    {
        return is_a($this->graphicControlExtension, GraphicControlExtension::class);
    }

    /**
     * Get graphic rendering block
     *
     * @return GraphicRenderingBlock
     */
    public function getGraphicRenderingBlock(): GraphicRenderingBlock
    {
        return $this->graphicRenderingBlock;
    }

    /**
     * Set graphic rendering block
     *
     * @param GraphicRenderingBlock $block
     */
    public function setGraphicRenderingBlock(GraphicRenderingBlock $block): self
    {
        $this->graphicRenderingBlock = $block;

        return $this;
    }
}
