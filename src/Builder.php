<?php

namespace Intervention\Gif;

use Intervention\Gif\Traits\CanHandleFiles;

class Builder
{
    use CanHandleFiles;

    /**
     * Gif object to build
     *
     * @var GifDataStream
     */
    protected $gif;

    /**
     * Get GifDataStream object we're currently building
     *
     * @return GifDataStream
     */
    public function getGifDataStream(): GifDataStream
    {
        return $this->gif;
    }

    /**
     * Create new canvas
     *
     * @param  int         $width
     * @param  int         $height
     * @param  int         $loops
     * @return self
     */
    public static function canvas(int $width, int $height, int $loops = 0): self
    {
        $builder = new self();
        $gif = new GifDataStream();
        $gif->getLogicalScreen()->getDescriptor()->setSize($width, $height);

        if ($loops >= 0 && $loops !== 1) {
            $gif->addData(new ApplicationExtension());
            $gif->getMainApplicationExtension()->setLoops($loops);
        }

        $builder->gif = $gif;

        return $builder;
    }

    /**
     * Create new animation frame from given source
     * which can be path to a file or GIF image data
     *
     * @param string  $source
     * @param integer $delay   time delay in seconds
     * @param integer $left    position offset in pixels from left
     * @param integer $top     position offset in pixels from top
     */
    public function addFrame(string $source, float $delay = 0, int $left = 0, int $top = 0): self
    {
        $data = new GraphicBlock();

        // store delay
        $data->setGraphicControlExtension(
            $this->buildGraphicControlExtension(intval($delay * 100))
        );

        // store image
        $data->setGraphicRenderingBlock(
            $this->buildTableBasedImage($source, $left, $top)
        );

        // add frame
        $this->gif->addData($data);

        return $this;
    }

    /**
     * Build new graphic control extension object with given delay
     *
     * @param  float $delay
     * @return GraphicControlExtension
     */
    protected function buildGraphicControlExtension(int $delay): GraphicControlExtension
    {
        $extension = new GraphicControlExtension();
        $extension->setDelay($delay);

        return $extension;
    }

    /**
     * Build table based image object from given source
     *
     * @param  string $source
     * @param  int    $left
     * @param  int    $top
     * @return TableBasedImage
     */
    protected function buildTableBasedImage(string $source, int $left, int $top): TableBasedImage
    {
        $block = new TableBasedImage();

        $source = Decoder::decode($source);

        // add global color table from source as local color table
        $block->getDescriptor()->setLocalColorTableExistance();
        $block->setColorTable(
            $source->getLogicalScreen()->getColorTable()
        );
        $block->getDescriptor()->setLocalColorTableSorted(
            $source->getLogicalScreen()->getDescriptor()->getGlobalColorTableSorted()
        );
        $block->getDescriptor()->setLocalColorTableSize(
            $source->getLogicalScreen()->getDescriptor()->getGlobalColorTableSize()
        );
        $block->getDescriptor()->setSize(
            $source->getLogicalScreen()->getDescriptor()->getWidth(),
            $source->getLogicalScreen()->getDescriptor()->getHeight()
        );

        // set position
        $block->getDescriptor()->setPosition($left, $top);

        // add image data from source
        $block->setData(
            $source->getData()[0]->getGraphicRenderingBlock()->getData()
        );

        return $block;
    }

    /**
     * Encode the current build
     *
     * @return string
     */
    public function encode(): string
    {
        return $this->gif->encode();
    }
}
