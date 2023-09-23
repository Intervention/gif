<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Builder;
use Intervention\Gif\Decoder;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Splitter;

class PipelineTest extends BaseTestCase
{
    public function testPipeline(): void
    {
        $source = file_get_contents(__DIR__ . '/images/animation2.gif');
        $gif = Decoder::decode($source);

        $this->validateGif($gif);

        $splitter = Splitter::create($gif)->split();
        $delays = $splitter->getDelays();
        $gd_objects = $splitter->coalesceToResources();
        foreach ($gd_objects as $gd) {
            $this->assertEquals(30, imagesx($gd));
            $this->assertEquals(20, imagesy($gd));
        }

        $builder = Builder::canvas(30, 20);
        foreach ($gd_objects as $gd) {
            $framesrc = $this->getBuffered(function () use ($gd) {
                imagecolortransparent($gd);
                imagegif($gd);
            });
            $builder->addFrame($framesrc, 13);
        }

        // reread
        $builder->encode();

        $regif = Decoder::decode($source);
        $this->validateGif($regif);
    }

    protected function validateGif(GifDataStream $gif): void
    {
        $this->assertInstanceOf(GifDataStream::class, $gif);

        // global color table
        $this->assertEquals(true, $gif->getLogicalScreen()->getDescriptor()->hasGlobalColorTable());
        $this->assertEquals(false, $gif->getLogicalScreen()->getDescriptor()->getGlobalColorTableSorted());
        $this->assertEquals(7, $gif->getLogicalScreen()->getDescriptor()->getGlobalColorTableSize());
        $this->assertEquals(256 * 3, $gif->getLogicalScreen()->getDescriptor()->getGlobalColorTableByteSize());
        $this->assertEquals(0, $gif->getLogicalScreen()->getDescriptor()->getBackgroundColorIndex());

        $this->assertEquals(0, $gif->getMainApplicationExtension()->getLoops()); // loops

        // frames
        $this->assertCount(6, $gif->getGraphicBlocks());
        foreach ($gif->getGraphicBlocks() as $block) {
            $this->assertEquals(13, $block->getGraphicControlExtension()->getDelay()); // delay
            $this->assertEquals(1, $block->getGraphicControlExtension()->getDisposalMethod()); // disposal
            $this->assertFalse($block->getGraphicRenderingBlock()->hasColorTable()); // local color table
            $this->assertFalse($block->getGraphicRenderingBlock()->getDescriptor()->isInterlaced()); // interlaced
        }
    }

    protected function getBuffered(callable $callback): string
    {
        ob_start();
        $callback();
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
}
