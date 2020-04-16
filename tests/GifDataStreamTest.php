<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\ColorTable;
use Intervention\Gif\CommentExtension;
use Intervention\Gif\DisposalMethod;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\GraphicBlock;
use Intervention\Gif\GraphicControlExtension;
use Intervention\Gif\Header;
use Intervention\Gif\LogicalScreen;
use Intervention\Gif\TableBasedImage;

class GifDataStreamTest extends BaseTestCase
{
    private function getGifDataStream()
    {
        $gif = new GifDataStream;
        $gif->addData(new ApplicationExtension);
        $gif->addData((new CommentExtension)->addComment('foobar'));
        $gif->addData((new GraphicBlock)->setGraphicControlExtension(new GraphicControlExtension));

        return $gif;
    }

    public function testSetGetHeader()
    {
        $gif = new GifDataStream;
        $gif->setHeader(new Header);
        $this->assertInstanceOf(Header::class, $gif->getHeader());
    }

    public function testSetGetLogicalScreen()
    {
        $gif = new GifDataStream;
        $gif->setLogicalScreen(new LogicalScreen);
        $this->assertInstanceOf(LogicalScreen::class, $gif->getLogicalScreen());
    }

    public function testSetGetData()
    {
        $gif = new GifDataStream;
        $this->assertIsArray($gif->getData());
        $this->assertCount(0, $gif->getData());
        $gif->addData(new GraphicBlock);
        $gif->addData(new GraphicBlock);
        $this->assertCount(2, $gif->getData());
    }

    public function testGetMainApplicationExtension()
    {
        $gif = new GifDataStream;
        $extension = new ApplicationExtension;
        $gif->addData(new GraphicBlock);
        $gif->addData($extension);
        $gif->addData(new GraphicBlock);
        $this->assertEquals($extension, $gif->getMainApplicationExtension());
    }

    // public function testEncode()
    // {
    //     $gif = $this->getGifDataStream();

    //     $this->assertEquals("\x47\x49\x46\x38\x37\x61\x00\x00\x00\x00\x70\x00\x00\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\x00\x00\x00\x21\xfe\x06\x66\x6f\x6f\x62\x61\x72\x00\x21\xf9\x04\x00\x00\x00\x00\x00\x2c\x00\x00\x00\x00\x00\x00\x00\x00\x00\x3b", $gif->encode());
    // }

    public function testDecode()
    {
        $source = file_get_contents(__DIR__.'/images/animation.gif');
        $gif = GifDataStream::decode($this->getTestHandle($source));
        $this->assertInstanceOf(GifDataStream::class, $gif);

        // HEADER
        $this->assertEquals('89a', $gif->getHeader()->getVersion());
        
        // LOGICAL SCREEN DESCRIPTOR
        $this->assertEquals(20, $gif->getLogicalScreen()->getDescriptor()->getWidth());
        $this->assertEquals(15, $gif->getLogicalScreen()->getDescriptor()->getHeight());
        $this->assertTrue($gif->getLogicalScreen()->getDescriptor()->hasGlobalColorTable());
        $this->assertFalse($gif->getLogicalScreen()->getDescriptor()->getGlobalColorTableSorted());
        $this->assertEquals(4, $gif->getLogicalScreen()->getDescriptor()->getGlobalColorTableSize());
        $this->assertEquals(7, $gif->getLogicalScreen()->getDescriptor()->getBackgroundColorIndex());
        $this->assertEquals(0, $gif->getLogicalScreen()->getDescriptor()->getPixelAspectRatio());
        $this->assertEquals(8, $gif->getLogicalScreen()->getDescriptor()->getBitsPerPixel());

        // GLOBAL COLOR TABLE
        $this->assertInstanceOf(ColorTable::class, $gif->getLogicalScreen()->getColorTable());
        $this->assertEquals(32, $gif->getLogicalScreen()->getColorTable()->countColors());

        // APPLICATION EXTENSION
        $this->assertInstanceOf(ApplicationExtension::class, $gif->getMainApplicationExtension());
        $this->assertEquals(2, $gif->getMainApplicationExtension()->getLoops());
        
        // TABLE BASED IMAGES
        $this->assertCount(8, $gif->getGraphicBlocks());

        // GRAPHICS CONTROL EXTENSIONS
        $colortables = array_values(array_map(function ($block) {
            return $block->getGraphicRenderingBlock()->hasColorTable();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals(array_fill(0, 8, false), $colortables);

        $delays = array_values(array_map(function ($block) {
            return $block->getGraphicControlExtension()->getDelay();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals(array_fill(0, 8, 20), $delays);

        $usrinputs = array_values(array_map(function ($block) {
            return $block->getGraphicControlExtension()->getUserInput();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals(array_fill(0, 8, false), $usrinputs);

        $disposals = array_values(array_map(function ($block) {
            return $block->getGraphicControlExtension()->getDisposalMethod();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals(array_fill(0, 8, DisposalMethod::LEAVE), $disposals);

        $indexes = array_values(array_map(function ($block) {
            return $block->getGraphicControlExtension()->getTransparentColorIndex();
        }, $gif->getGraphicBlocks()));
        $this->assertEquals([255, 0, 0, 0, 1, 1, 1, 1], $indexes);

        // IMAGE DESCRIPTORS
        $lefts = array_values(array_map(function ($desc) {
            return $desc->getLeft();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([0, 5, 1, 0, 8, 5, 1, 0], $lefts);

        $tops = array_values(array_map(function ($desc) {
            return $desc->getTop();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([0, 2, 0, 0, 5, 2, 0, 0], $tops);

        $widths = array_values(array_map(function ($desc) {
            return $desc->getWidth();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([20, 10, 17, 20, 5, 10, 17, 20], $widths);

        $heights = array_values(array_map(function ($desc) {
            return $desc->getHeight();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([15, 10, 15, 15, 5, 10, 15, 15], $heights);

        $localcolortables = array_values(array_map(function ($desc) {
            return $desc->hasLocalColorTable();
        }, $gif->getImageDescriptors()));
        $this->assertEquals(array_fill(0, 8, false), $localcolortables);

        $interlaces = array_values(array_map(function ($desc) {
            return $desc->isInterlaced();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([true, false, false, false, false, false, false, false], $interlaces);

        $sorts = array_values(array_map(function ($desc) {
            return $desc->getLocalColorTableSorted();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([false, false, false, false, false, false, false, false], $sorts);

        $sizes = array_values(array_map(function ($desc) {
            return $desc->getLocalColorTableSize();
        }, $gif->getImageDescriptors()));
        $this->assertEquals([0, 0, 0, 0, 0, 0, 0, 0], $sizes);
    }
}
