<?php

namespace Intervention\Gif;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Splitter implements IteratorAggregate
{
    /**
     * Stream to split
     *
     * @var GifDataStream
     */
    protected $stream;

    /**
     * Single frames
     *
     * @var array
     */
    protected $frames = [];

    /**
     * Delays of each frame
     *
     * @var array
     */
    protected $delays = [];

    /**
     * Create new instance
     *
     * @param GifDataStream $stream
     */
    public function __construct(GifDataStream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Iterator
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->frames);
    }

    public function getFrames(): array
    {
        return $this->frames;
    }

    public function getDelays(): array
    {
        return $this->delays;
    }

    /**
     * Set stream of instance
     *
     * @param GifDataStream $stream
     */
    public function setStream(GifDataStream $stream): Splitter
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * Static constructor method
     *
     * @param  GifDataStream $stream
     * @return Splitter
     */
    public static function create(GifDataStream $stream): Splitter
    {
        return new self($stream);
    }

    /**
     * Split current stream into array of seperate streams for each frame
     *
     * @return Splitter
     */
    public function split(): self
    {
        $this->frames = [];

        foreach ($this->stream->getGraphicBlocks() as $block) {
            // create separate stream for each frame
            $frame = Builder::canvas(
                $this->stream->getLogicalScreen()->getDescriptor()->getWidth(),
                $this->stream->getLogicalScreen()->getDescriptor()->getHeight()
            )->getGifDataStream();

            // check if working stream has global color table
            if ($this->stream->getLogicalScreen()->getDescriptor()->hasGlobalColorTable()) {
                $frame->getLogicalScreen()->setColorTable(
                    $this->stream->getLogicalScreen()->getColorTable()
                );

                $frame->getLogicalScreen()->getDescriptor()->setGlobalColorTableExistance(
                    true
                );
                $frame->getLogicalScreen()->getDescriptor()->setGlobalColorTableSorted(
                    $this->stream->getLogicalScreen()->getDescriptor()->getGlobalColorTableSorted()
                );
                $frame->getLogicalScreen()->getDescriptor()->setGlobalColorTableSize(
                    $this->stream->getLogicalScreen()->getDescriptor()->getGlobalColorTableSize()
                );
                $frame->getLogicalScreen()->getDescriptor()->setBackgroundColorIndex(
                    $this->stream->getLogicalScreen()->getDescriptor()->getBackgroundColorIndex()
                );
                $frame->getLogicalScreen()->getDescriptor()->setPixelAspectRatio(
                    $this->stream->getLogicalScreen()->getDescriptor()->getPixelAspectRatio()
                );
                $frame->getLogicalScreen()->getDescriptor()->setBitsPerPixel(
                    $this->stream->getLogicalScreen()->getDescriptor()->getBitsPerPixel()
                );
            }

            // copy original block
            $frame->addData($block);

            $this->frames[] = $frame;
            $this->delays[] = $block->getGraphicControlExtension()->getDelay();
        }

        return $this;
    }

    /**
     * Return array of GD library resources for each frame
     *
     * @return array
     */
    public function toResources(): array
    {
        $resources = [];

        foreach ($this->frames as $frame) {
            if (is_a($frame, GifDataStream::class)) {
                $resources[] = imagecreatefromstring($frame->encode());
            }
        }

        return $resources;
    }

    /**
     * Return array of coalesced GD library resources for each frame
     *
     * @return array
     */
    public function coalesceToResources(): array
    {
        $resources = $this->toResources();
        $base = $resources[0];
        $width = imagesx($base);
        $height = imagesy($base);
        foreach ($resources as $key => $resource) {
            if ($key >= 1) {
                $descriptor = $this->frames[$key]->getTableBasedImages()[0]->getDescriptor();
                $offset_x = $descriptor->getLeft();
                $offset_y = $descriptor->getTop();
                $w = $descriptor->getWidth();
                $h = $descriptor->getHeight();

                // create new
                $new = imagecreatetruecolor($width, $height);
                imagealphablending($new, true);

                // insert last as base
                imagecopy(
                    $new,
                    $resources[$key - 1],
                    0,
                    0,
                    0,
                    0,
                    $width,
                    $height
                );

                // copy over current
                imagecopy(
                    $new,
                    $resource,
                    $offset_x,
                    $offset_y,
                    0,
                    0,
                    $w,
                    $h
                );


                $resources[$key] = $new;
            }
        }

        return $resources;
    }
}
