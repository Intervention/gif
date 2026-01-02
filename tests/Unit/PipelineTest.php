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
        $source = file_get_contents($this->imagePath('animation2.gif'));
        $gif = Decoder::decode($source);

        $this->validateGif($gif);

        $splitter = Splitter::create($gif)->split();
        $this->assertEquals(array_fill(0, 6, 13), $splitter->delays());

        $gdObjects = $splitter->flatten();
        foreach ($gdObjects as $gd) {
            $this->assertEquals(30, imagesx($gd));
            $this->assertEquals(20, imagesy($gd));
        }

        $builder = Builder::canvas(30, 20);
        foreach ($gdObjects as $gd) {
            $framesrc = $this->buffered(function () use ($gd): void {
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
        $this->assertEquals(true, $gif->logicalScreenDescriptor()->hasGlobalColorTable());
        $this->assertEquals(false, $gif->logicalScreenDescriptor()->globalColorTableSorted());
        $this->assertEquals(7, $gif->logicalScreenDescriptor()->globalColorTableSize());
        $this->assertEquals(256 * 3, $gif->logicalScreenDescriptor()->globalColorTableByteSize());
        $this->assertEquals(0, $gif->logicalScreenDescriptor()->backgroundColorIndex());

        $this->assertEquals(0, $gif->mainApplicationExtension()->loops()); // loops

        // frames
        $this->assertCount(6, $gif->frames());
        foreach ($gif->frames() as $frame) {
            $this->assertEquals(13, $frame->graphicControlExtension()->delay()); // delay
            $this->assertEquals(DisposalMethod::NONE, $frame->graphicControlExtension()->disposalMethod());
            $this->assertFalse($frame->hasColorTable()); // local color table
            $this->assertFalse($frame->imageDescriptor()->isInterlaced()); // interlaced
        }
    }

    protected function buffered(callable $callback): string
    {
        ob_start();
        $callback();
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
}
