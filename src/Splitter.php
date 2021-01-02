<?php

namespace Intervention\Gif;

class Splitter
{
    /**
     * Stream to split
     *
     * @var GifDataStream
     */
    protected $stream;

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
     * @return array
     */
    public function split(): array
    {
        $gifs = [];

        foreach ($this->stream->getGraphicBlocks() as $k => $block) {
            // create separate stream for each frame
            $build = Builder::canvas(
                $this->stream->getLogicalScreen()->getDescriptor()->getWidth(),
                $this->stream->getLogicalScreen()->getDescriptor()->getHeight()
            )->getGifDataStream();

            // check if working stream has global color table
            if ($this->stream->getLogicalScreen()->getDescriptor()->hasGlobalColorTable()) {
                $build->getLogicalScreen()->setColorTable(
                    $this->stream->getLogicalScreen()->getColorTable()
                );

                $build->getLogicalScreen()->getDescriptor()->setGlobalColorTableExistance(
                    true
                );
                $build->getLogicalScreen()->getDescriptor()->setGlobalColorTableSorted(
                    $this->stream->getLogicalScreen()->getDescriptor()->getGlobalColorTableSorted()
                );
                $build->getLogicalScreen()->getDescriptor()->setGlobalColorTableSize(
                    $this->stream->getLogicalScreen()->getDescriptor()->getGlobalColorTableSize()
                );
                $build->getLogicalScreen()->getDescriptor()->setBackgroundColorIndex(
                    $this->stream->getLogicalScreen()->getDescriptor()->getBackgroundColorIndex()
                );
                $build->getLogicalScreen()->getDescriptor()->setPixelAspectRatio(
                    $this->stream->getLogicalScreen()->getDescriptor()->getPixelAspectRatio()
                );
                $build->getLogicalScreen()->getDescriptor()->setBitsPerPixel(
                    $this->stream->getLogicalScreen()->getDescriptor()->getBitsPerPixel()
                );
            }

            // copy original block
            $build->addData($block);

            $gifs[] = $build;
        }

        return $gifs;
    }
}
