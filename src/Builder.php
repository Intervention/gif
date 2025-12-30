<?php

declare(strict_types=1);

namespace Intervention\Gif;

use Intervention\Gif\Blocks\FrameBlock;
use Intervention\Gif\Blocks\GraphicControlExtension;
use Intervention\Gif\Blocks\ImageDescriptor;
use Intervention\Gif\Blocks\NetscapeApplicationExtension;
use Intervention\Gif\Blocks\TableBasedImage;
use Intervention\Gif\Exceptions\DecoderException;
use Intervention\Gif\Exceptions\EncoderException;
use Intervention\Gif\Exceptions\FilePointerException;
use Intervention\Gif\Exceptions\InvalidArgumentException;
use Intervention\Gif\Exceptions\StateException;
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
     *
     * @throws InvalidArgumentException
     */
    public static function canvas(int $width, int $height): self
    {
        return (new self())->setSize($width, $height);
    }

    /**
     * Get GifDataStream object we're currently building
     */
    public function gifDataStream(): GifDataStream
    {
        return $this->gif;
    }

    /**
     * Set canvas size of gif
     *
     * @throws InvalidArgumentException
     */
    public function setSize(int $width, int $height): self
    {
        $this->gif->logicalScreenDescriptor()->setSize($width, $height);

        return $this;
    }

    /**
     * Set loop count
     *
     * @throws StateException
     * @throws InvalidArgumentException
     */
    public function setLoops(int $loops): self
    {
        if ($this->gif->frames() === []) {
            throw new StateException('Add at least one frame before setting the loop count');
        }

        if ($loops >= 0) {
            // add frame count to existing or new netscape extension on first frame
            if (!$this->gif->firstFrame()->netscapeExtension()) {
                $this->gif->firstFrame()->addApplicationExtension(
                    new NetscapeApplicationExtension()
                );
            }
            $this->gif->firstFrame()->netscapeExtension()->setLoops($loops);
        }

        return $this;
    }

    /**
     * Create new animation frame from given source
     * which can be path to a file or GIF image data
     *
     * @throws DecoderException
     * @throws FilePointerException
     * @throws InvalidArgumentException
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
        $control = $source->firstFrame()->graphicControlExtension();
        if ($control && $control->transparentColorExistance()) {
            $extension->setTransparentColorExistance();
            $extension->setTransparentColorIndex(
                $control->transparentColorIndex()
            );
        }

        return $extension;
    }

    /**
     * Build table based image object from given source
     *
     * @throws DecoderException
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
        $block->imageDescriptor()->setLocalColorTableExistance();
        $block->setColorTable($source->globalColorTable());

        $block->imageDescriptor()->setLocalColorTableSorted(
            $source->logicalScreenDescriptor()->globalColorTableSorted()
        );

        try {
            $block->imageDescriptor()->setLocalColorTableSize(
                $source->logicalScreenDescriptor()->globalColorTableSize()
            );

            $block->imageDescriptor()->setSize(
                $source->logicalScreenDescriptor()->width(),
                $source->logicalScreenDescriptor()->height()
            );
        } catch (InvalidArgumentException $e) {
            throw new DecoderException(
                'Failed to decode image source',
                previous: $e
            );
        }

        // set position
        $block->imageDescriptor()->setPosition($left, $top);

        // set interlaced flag
        $block->imageDescriptor()->setInterlaced($interlaced);

        // add image data from source
        $block->setImageData($source->firstFrame()->imageData());

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
