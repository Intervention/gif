<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Builder;
use Intervention\Gif\Decoder;
use Intervention\Gif\DisposalMethod;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Splitter;
use Intervention\Gif\Tests\BaseTestCase;

final class PipelineTest extends BaseTestCase
{
    public function testPipeline(): void
    {
        $source = file_get_contents($this->getTestImagePath('animation2.gif'));
        $gif = Decoder::decode($source);

        $this->validateGif($gif);

        $splitter = Splitter::create($gif)->split();
        $this->assertEquals(array_fill(0, 6, 13), $splitter->getDelays());

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
        $this->assertEquals(true, $gif->getLogicalScreenDescriptor()->hasGlobalColorTable());
        $this->assertEquals(false, $gif->getLogicalScreenDescriptor()->getGlobalColorTableSorted());
        $this->assertEquals(7, $gif->getLogicalScreenDescriptor()->getGlobalColorTableSize());
        $this->assertEquals(256 * 3, $gif->getLogicalScreenDescriptor()->getGlobalColorTableByteSize());
        $this->assertEquals(0, $gif->getLogicalScreenDescriptor()->getBackgroundColorIndex());

        $this->assertEquals(0, $gif->getMainApplicationExtension()->getLoops()); // loops

        // frames
        $this->assertCount(6, $gif->getFrames());
        foreach ($gif->getFrames() as $frame) {
            $this->assertEquals(13, $frame->getGraphicControlExtension()->getDelay()); // delay
            $this->assertEquals(DisposalMethod::NONE, $frame->getGraphicControlExtension()->getDisposalMethod());
            $this->assertFalse($frame->hasColorTable()); // local color table
            $this->assertFalse($frame->getImageDescriptor()->isInterlaced()); // interlaced
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
