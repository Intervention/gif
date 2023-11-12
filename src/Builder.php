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

        // set width +height
        $gif->getLogicalScreen()->getDescriptor()->setSize($width, $height);

        if ($loops >= 0 && $loops !== 1) {
            // set loop count
            $gif->addData(
                (new NetscapeApplicationExtension())->setLoops($loops)
            );
        }

        $builder->gif = $gif;

        return $builder;
    }

    /**
     * Create new animation frame from given source
     * which can be path to a file or GIF image data
     *
     * @param string  $source
     * @param float $delay   time delay in seconds
     * @param int $left    position offset in pixels from left
     * @param int $top     position offset in pixels from top
     * @return Builder
     */
    public function addFrame(string $source, float $delay = 0, int $left = 0, int $top = 0): self
    {
        $data = new GraphicBlock();
        $source = Decoder::decode($source);

        // store delay
        $data->setGraphicControlExtension(
            $this->buildGraphicControlExtension(
                $source,
                intval($delay * 100)
            )
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
     * @param  int $delay
     * @return GraphicControlExtension
     */
    protected function buildGraphicControlExtension(
        GifDataStream $source,
        int $delay,
        int $disposal_method = DisposalMethod::BACKGROUND
    ): GraphicControlExtension {
        $extension = new GraphicControlExtension();

        // set delay
        $extension->setDelay($delay);

        // set DisposalMethod
        $extension->setDisposalMethod($disposal_method);

        // set transparency index
        $control = $source->getGraphicBlocks()[0]->getGraphicControlExtension();
        if ($control && $control->getTransparentColorExistance()) {
            $extension->setTransparentColorExistance();
            $extension->setTransparentColorIndex(
                $control->getTransparentColorIndex()
            );
        }

        return $extension;
    }

    /**
     * Build table based image object from given source
     *
     * @param  GifDataStream $source
     * @param  int    $left
     * @param  int    $top
     * @return TableBasedImage
     */
    protected function buildTableBasedImage(GifDataStream $source, int $left, int $top): TableBasedImage
    {
        $block = new TableBasedImage();

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

    public function modifyGif(callable $callback): self
    {
        $callback($this->gif);

        return $this;
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
