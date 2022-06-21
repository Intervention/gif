<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\ColorTable;
use Intervention\Gif\Decoder;
use Intervention\Gif\DisposalMethod;
use Intervention\Gif\GifDataStream;

class AnimationDecodingTest extends BaseTestCase
{
    public function testDecodeAnimation()
    {
        $gif = Decoder::decode(__DIR__ . '/images/animation2.gif');
        $this->assertInstanceOf(GifDataStream::class, $gif);

        // HEADER
        $this->assertEquals('89a', $gif->getHeader()->getVersion());

        // LOGICAL SCREEN DESCRIPTOR
        $this->assertEquals(30, $gif->getLogicalScreen()->getDescriptor()->getWidth());
        $this->assertEquals(20, $gif->getLogicalScreen()->getDescriptor()->getHeight());
        $this->assertTrue($gif->getLogicalScreen()->getDescriptor()->hasGlobalColorTable());
        $this->assertFalse($gif->getLogicalScreen()->getDescriptor()->getGlobalColorTableSorted());
        $this->assertEquals(7, $gif->getLogicalScreen()->getDescriptor()->getGlobalColorTableSize());
        $this->assertEquals(0, $gif->getLogicalScreen()->getDescriptor()->getBackgroundColorIndex());
        $this->assertEquals(0, $gif->getLogicalScreen()->getDescriptor()->getPixelAspectRatio());
        $this->assertEquals(1, $gif->getLogicalScreen()->getDescriptor()->getBitsPerPixel());

        // GLOBAL COLOR TABLE
        $this->assertInstanceOf(ColorTable::class, $gif->getLogicalScreen()->getColorTable());
        $this->assertEquals(256, $gif->getLogicalScreen()->getColorTable()->countColors());

        // APPLICATION EXTENSION
        $this->assertInstanceOf(ApplicationExtension::class, $gif->getMainApplicationExtension());
        $this->assertEquals(0, $gif->getMainApplicationExtension()->getLoops());

        // TABLE BASED IMAGES
        $this->assertCount(6, $gif->getGraphicBlocks());

        // GRAPHICS CONTROL EXTENSIONS
        $colortables = array_values(array_map(function ($block) {
            return $block->getGraphicRenderingBlock()->hasColorTable();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals(array_fill(0, 6, false), $colortables);

        $delays = array_values(array_map(function ($block) {
            return $block->getGraphicControlExtension()->getDelay();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals(array_fill(0, 6, 13), $delays);

        $usrinputs = array_values(array_map(function ($block) {
            return $block->getGraphicControlExtension()->getUserInput();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals(array_fill(0, 6, false), $usrinputs);

        $disposals = array_values(array_map(function ($block) {
            return $block->getGraphicControlExtension()->getDisposalMethod();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals(array_fill(0, 6, DisposalMethod::NONE), $disposals);

        $indexes = array_values(array_map(function ($block) {
            return $block->getGraphicControlExtension()->getTransparentColorIndex();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals(array_fill(0, 6, 2), $indexes);

        // IMAGE DESCRIPTORS
        $lefts = array_values(array_map(function ($desc) {
            return $desc->getLeft();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([0, 9, 5, 0, 9, 5], $lefts);

        $tops = array_values(array_map(function ($desc) {
            return $desc->getTop();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([0, 6, 3, 0, 6, 3], $tops);

        $widths = array_values(array_map(function ($desc) {
            return $desc->getWidth();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([30, 12, 20, 30, 12, 20], $widths);

        $heights = array_values(array_map(function ($desc) {
            return $desc->getHeight();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([20, 8, 14, 20, 8, 14], $heights);

        $localcolortables = array_values(array_map(function ($desc) {
            return $desc->hasLocalColorTable();
        }, $gif->getImageDescriptors()));
        $this->assertEquals(array_fill(0, 6, false), $localcolortables);

        $interlaces = array_values(array_map(function ($desc) {
            return $desc->isInterlaced();
        }, $gif->getImageDescriptors()));
        $this->assertEquals(array_fill(0, 6, false), $interlaces);

        $sorts = array_values(array_map(function ($desc) {
            return $desc->getLocalColorTableSorted();
        }, $gif->getImageDescriptors()));
        $this->assertEquals(array_fill(0, 6, false), $sorts);

        $sizes = array_values(array_map(function ($desc) {
            return $desc->getLocalColorTableSize();
        }, $gif->getImageDescriptors()));
        $this->assertEquals(array_fill(0, 6, 0), $sizes);
    }
}
