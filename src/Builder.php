<?php

declare(strict_types=1);

namespace Intervention\Gif;

use Exception;
use Intervention\Gif\Blocks\FrameBlock;
use Intervention\Gif\Blocks\GraphicControlExtension;
use Intervention\Gif\Blocks\ImageDescriptor;
use Intervention\Gif\Blocks\NetscapeApplicationExtension;
use Intervention\Gif\Blocks\TableBasedImage;
use Intervention\Gif\Exceptions\DecoderException;
use Intervention\Gif\Exceptions\EncoderException;
use Intervention\Gif\Traits\CanHandleFiles;

class Builder
{
    use CanHandleFiles;

    /**
     * Create new instance
     */
    public function __construct(protected GifDataStream $gif = new GifDataStream())
    {
        //
    }

    /**
     * Create new canvas
     */
    public static function canvas(int $width, int $height): self
    {
        return (new self())->setSize($width, $height);
    }

    /**
     * Get GifDataStream object we're currently building
     */
    public function getGifDataStream(): GifDataStream
    {
        return $this->gif;
    }

    /**
     * Set canvas size of gif
     */
    public function setSize(int $width, int $height): self
    {
        $this->gif->getLogicalScreenDescriptor()->setSize($width, $height);

        return $this;
    }

    /**
     * Set loop count
     *
     * @throws Exception
     */
    public function setLoops(int $loops): self
    {
        if ($this->gif->getFrames() === []) {
            throw new Exception('Add at least one frame before setting the loop count');
        }

        if ($loops >= 0) {
            // add frame count to existing or new netscape extension on first frame
            if (!$this->gif->getFirstFrame()->getNetscapeExtension()) {
                $this->gif->getFirstFrame()->addApplicationExtension(
                    new NetscapeApplicationExtension()
                );
            }
            $this->gif->getFirstFrame()->getNetscapeExtension()->setLoops($loops);
        }

        return $this;
    }

    /**
     * Create new animation frame from given source
     * which can be path to a file or GIF image data
     *
     * @throws DecoderException
     */
    public function addFrame(
        mixed $source,
        float $delay = 0,
        int $left = 0,
        int $top = 0,
        bool $interlaced = false
    ): self {
        $frame = new FrameBlock();
        $source = Decoder::decode($source);

        // store delay
        $frame->setGraphicControlExtension(
            $this->buildGraphicControlExtension(
                $source,
                intval($delay * 100)
            )
        );

        // store image
        $frame->setTableBasedImage(
            $this->buildTableBasedImage($source, $left, $top, $interlaced)
        );

        // add frame
        $this->gif->addFrame($frame);

        return $this;
    }

    /**
     * Build new graphic control extension with given delay & disposal method
     */
    protected function buildGraphicControlExtension(
        GifDataStream $source,
        int $delay,
        DisposalMethod $disposalMethod = DisposalMethod::BACKGROUND
    ): GraphicControlExtension {
        // create extension
        $extension = new GraphicControlExtension($delay, $disposalMethod);

        // set transparency index
        $control = $source->getFirstFrame()->getGraphicControlExtension();
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
     */
    protected function buildTableBasedImage(
        GifDataStream $source,
        int $left,
        int $top,
        bool $interlaced
    ): TableBasedImage {
        $block = new TableBasedImage();
        $block->setImageDescriptor(new ImageDescriptor());

        // set global color table from source as local color table
        $block->getImageDescriptor()->setLocalColorTableExistance();
        $block->setColorTable($source->getGlobalColorTable());

        $block->getImageDescriptor()->setLocalColorTableSorted(
            $source->getLogicalScreenDescriptor()->getGlobalColorTableSorted()
        );

        $block->getImageDescriptor()->setLocalColorTableSize(
            $source->getLogicalScreenDescriptor()->getGlobalColorTableSize()
        );

        $block->getImageDescriptor()->setSize(
            $source->getLogicalScreenDescriptor()->getWidth(),
            $source->getLogicalScreenDescriptor()->getHeight()
        );

        // set position
        $block->getImageDescriptor()->setPosition($left, $top);

        // set interlaced flag
        $block->getImageDescriptor()->setInterlaced($interlaced);

        // add image data from source
        $block->setImageData($source->getFirstFrame()->getImageData());

        return $block;
    }

    /**
     * Encode the current build
     *
     * @throws EncoderException
     */
    public function encode(): string
    {
        return $this->gif->encode();
    }
}
