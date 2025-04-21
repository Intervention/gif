<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Blocks\NetscapeApplicationExtension;
use Intervention\Gif\Decoder;
use Intervention\Gif\DisposalMethod;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Tests\BaseTestCase;

final class AnimationDecodingTest extends BaseTestCase
{
    public function testDecodeAnimation(): void
    {
        $gif = Decoder::decode($this->testImagePath('animation2.gif'));
        $this->assertInstanceOf(GifDataStream::class, $gif);

        // header
        $this->assertEquals('89a', $gif->header()->version());

        // logical screen descriptor
        $this->assertEquals(30, $gif->logicalScreenDescriptor()->width());
        $this->assertEquals(20, $gif->logicalScreenDescriptor()->height());
        $this->assertTrue($gif->logicalScreenDescriptor()->hasGlobalColorTable());
        $this->assertFalse($gif->logicalScreenDescriptor()->globalColorTableSorted());
        $this->assertEquals(7, $gif->logicalScreenDescriptor()->globalColorTableSize());
        $this->assertEquals(0, $gif->logicalScreenDescriptor()->backgroundColorIndex());
        $this->assertEquals(0, $gif->logicalScreenDescriptor()->pixelAspectRatio());
        $this->assertEquals(1, $gif->logicalScreenDescriptor()->bitsPerPixel());

        // global color table
        $this->assertInstanceOf(ColorTable::class, $gif->globalColorTable());
        $this->assertEquals(256, $gif->globalColorTable()->countColors());

        // netscape application extension
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $gif->mainApplicationExtension());
        $this->assertEquals(0, $gif->mainApplicationExtension()->loops());

        // frame count
        $this->assertCount(6, $gif->frames());

        // local color tables in each frame
        $colortables = array_values(array_map(function ($frame) {
            return $frame->hasColorTable();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 6, false), $colortables);

        // delay in each frame
        $delays = array_values(array_map(function ($frame) {
            return $frame->graphicControlExtension()->delay();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 6, 13), $delays);

        // userinput in each frame
        $userInputs = array_values(array_map(function ($frame) {
            return $frame->graphicControlExtension()->userInput();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 6, false), $userInputs);

        // disposal flag in each frame
        $disposals = array_values(array_map(function ($frame) {
            return $frame->graphicControlExtension()->disposalMethod();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 6, DisposalMethod::NONE), $disposals);

        $indexes = array_values(array_map(function ($frame) {
            return $frame->graphicControlExtension()->transparentColorIndex();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 6, 2), $indexes);

        // left pos. in each frame
        $lefts = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->left();
        }, $gif->frames()));
        $this->assertEquals([0, 9, 5, 0, 9, 5], $lefts);

        // top pos. in each frame
        $tops = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->top();
        }, $gif->frames()));
        $this->assertEquals([0, 6, 3, 0, 6, 3], $tops);

        // width in each frame
        $widths = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->width();
        }, $gif->frames()));
        $this->assertEquals([30, 12, 20, 30, 12, 20], $widths);

        // height in each frame
        $heights = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->height();
        }, $gif->frames()));
        $this->assertEquals([20, 8, 14, 20, 8, 14], $heights);

        // local color table in each frame
        $localcolortables = array_values(array_map(function ($frame) {
            return $frame->hasColorTable();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 6, false), $localcolortables);

        // interlace flag in each frame
        $interlaces = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->isInterlaced();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 6, false), $interlaces);

        // sort flag of each frame
        $sorts = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->localColorTableSorted();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 6, false), $sorts);

        // local color table size of each frame
        $sizes = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->localColorTableSize();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 6, 0), $sizes);
    }
}
