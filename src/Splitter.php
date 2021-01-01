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
            echo "<pre>";
            var_dump($block);
            echo "</pre>";
            exit;
            // create separate stream for each frame
            $stream = Builder::canvas(
                $this->stream->getLogicalScreen()->getDescriptor()->getWidth(),
                $this->stream->getLogicalScreen()->getDescriptor()->getHeight()
            )->getGifDataStream();

            // check if working stream has global color table
            if ($this->stream->getLogicalScreen()->getDescriptor()->hasGlobalColorTable()) {
                $stream->getLogicalScreen()->setColorTable(
                    $this->stream->getLogicalScreen()->getColorTable()
                );

                $stream->getLogicalScreen()->getDescriptor()->setGlobalColorTableExistance(
                    true
                );
                $stream->getLogicalScreen()->getDescriptor()->setGlobalColorTableSorted(
                    $this->stream->getLogicalScreen()->getDescriptor()->getGlobalColorTableSorted()
                );
                $stream->getLogicalScreen()->getDescriptor()->setGlobalColorTableSize(
                    $this->stream->getLogicalScreen()->getDescriptor()->getGlobalColorTableSize()
                );
                $stream->getLogicalScreen()->getDescriptor()->setBackgroundColorIndex(
                    $this->stream->getLogicalScreen()->getDescriptor()->getBackgroundColorIndex()
                );
                $stream->getLogicalScreen()->getDescriptor()->setPixelAspectRatio(
                    $this->stream->getLogicalScreen()->getDescriptor()->getPixelAspectRatio()
                );
                $stream->getLogicalScreen()->getDescriptor()->setBitsPerPixel(
                    $this->stream->getLogicalScreen()->getDescriptor()->getBitsPerPixel()
                );
            }

            // $block->getLogicalScreen()->getDescriptor()->setInterlaced(false);

            $block->getGraphicRenderingBlock()->getDescriptor()->setInterlaced(false);

            $stream->addData($block);

            file_put_contents(__DIR__ . '/tmp_frame_' . $k . '.gif', $stream->encode());

            $gifs[] = $stream;
        }

        return $gifs;
    }
}
