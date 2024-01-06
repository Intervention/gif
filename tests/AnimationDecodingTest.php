<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Blocks\NetscapeApplicationExtension;
use Intervention\Gif\Decoder;
use Intervention\Gif\DisposalMethod;
use Intervention\Gif\GifDataStream;

class AnimationDecodingTest extends BaseTestCase
{
    public function testDecodeAnimation()
    {
        $gif = Decoder::decode(__DIR__ . '/images/animation2.gif');
        $this->assertInstanceOf(GifDataStream::class, $gif);

        // header
        $this->assertEquals('89a', $gif->getHeader()->getVersion());

        // logical screen descriptor
        $this->assertEquals(30, $gif->getLogicalScreenDescriptor()->getWidth());
        $this->assertEquals(20, $gif->getLogicalScreenDescriptor()->getHeight());
        $this->assertTrue($gif->getLogicalScreenDescriptor()->hasGlobalColorTable());
        $this->assertFalse($gif->getLogicalScreenDescriptor()->getGlobalColorTableSorted());
        $this->assertEquals(7, $gif->getLogicalScreenDescriptor()->getGlobalColorTableSize());
        $this->assertEquals(0, $gif->getLogicalScreenDescriptor()->getBackgroundColorIndex());
        $this->assertEquals(0, $gif->getLogicalScreenDescriptor()->getPixelAspectRatio());
        $this->assertEquals(1, $gif->getLogicalScreenDescriptor()->getBitsPerPixel());

        // global color table
        $this->assertInstanceOf(ColorTable::class, $gif->getGlobalColorTable());
        $this->assertEquals(256, $gif->getGlobalColorTable()->countColors());

        // netscape application extension
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $gif->getMainApplicationExtension());
        $this->assertEquals(0, $gif->getMainApplicationExtension()->getLoops());

        // frame count
        $this->assertCount(6, $gif->getFrames());

        // local color tables in each frame
        $colortables = array_values(array_map(function ($frame) {
            return $frame->hasColorTable();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 6, false), $colortables);

        // delay in each frame
        $delays = array_values(array_map(function ($frame) {
            return $frame->getGraphicControlExtension()->getDelay();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 6, 13), $delays);

        // userinput in each frame
        $userInputs = array_values(array_map(function ($frame) {
            return $frame->getGraphicControlExtension()->getUserInput();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 6, false), $userInputs);

        // disposal flag in each frame
        $disposals = array_values(array_map(function ($frame) {
            return $frame->getGraphicControlExtension()->getDisposalMethod();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 6, DisposalMethod::NONE), $disposals);

        $indexes = array_values(array_map(function ($frame) {
            return $frame->getGraphicControlExtension()->getTransparentColorIndex();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 6, 2), $indexes);

        // left pos. in each frame
        $lefts = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getLeft();
        }, $gif->getFrames()));
        $this->assertEquals([0, 9, 5, 0, 9, 5], $lefts);

        // top pos. in each frame
        $tops = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getTop();
        }, $gif->getFrames()));
        $this->assertEquals([0, 6, 3, 0, 6, 3], $tops);

        // width in each frame
        $widths = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getWidth();
        }, $gif->getFrames()));
        $this->assertEquals([30, 12, 20, 30, 12, 20], $widths);

        // height in each frame
        $heights = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getHeight();
        }, $gif->getFrames()));
        $this->assertEquals([20, 8, 14, 20, 8, 14], $heights);

        // local color table in each frame
        $localcolortables = array_values(array_map(function ($frame) {
            return $frame->hasColorTable();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 6, false), $localcolortables);

        // interlace flag in each frame
        $interlaces = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->isInterlaced();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 6, false), $interlaces);

        // sort flag of each frame
        $sorts = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getLocalColorTableSorted();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 6, false), $sorts);

        // local color table size of each frame
        $sizes = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getLocalColorTableSize();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 6, 0), $sizes);
    }
}
