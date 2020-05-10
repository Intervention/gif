<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Color;
use Intervention\Gif\ColorTable;
use Intervention\Gif\LogicalScreenDescriptor;

class LogicalScreenDescriptorTest extends BaseTestCase
{
    public function testSetGetSize()
    {
        $descriptor = new LogicalScreenDescriptor();
        $result = $descriptor->setSize(300, 200);
        $this->assertEquals(300, $descriptor->getWidth());
        $this->assertEquals(200, $descriptor->getHeight());
        $this->assertInstanceOf(LogicalScreenDescriptor::class, $result);
    }

    public function testGlobalColorTableExistanceFlag()
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertFalse($descriptor->getGlobalColorTableExistance());

        $descriptor->setGlobalColorTableExistance();
        $this->assertTrue($descriptor->getGlobalColorTableExistance());

        $descriptor->setGlobalColorTableExistance(false);
        $this->assertFalse($descriptor->getGlobalColorTableExistance());
    }

    public function testGlobalColorTableSortFlag()
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertFalse($descriptor->getGlobalColorTableSorted());

        $descriptor->setGlobalColorTableSorted();
        $this->assertTrue($descriptor->getGlobalColorTableSorted());

        $descriptor->setGlobalColorTableSorted(false);
        $this->assertFalse($descriptor->getGlobalColorTableSorted());
    }

    public function testGlobalColorTableSize()
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertEquals(0, $descriptor->getGlobalColorTableSize());

        $descriptor->setGlobalColorTableSize(7);
        $this->assertEquals(7, $descriptor->getGlobalColorTableSize());

        $descriptor->setGlobalColorTableSize(2);
        $this->assertEquals(2, $descriptor->getGlobalColorTableSize());
    }

    public function testGlobalColorTableByteSize()
    {
        $descriptor = new LogicalScreenDescriptor(); // default: 0
        $this->assertEquals(6, $descriptor->getGlobalColorTableByteSize());

        $descriptor->setGlobalColorTableSize(7);
        $this->assertEquals(768, $descriptor->getGlobalColorTableByteSize());

        $descriptor->setGlobalColorTableSize(2);
        $this->assertEquals(24, $descriptor->getGlobalColorTableByteSize());
    }

    public function testBackgroundColorIndex()
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertEquals(0, $descriptor->getBackgroundColorIndex());

        $descriptor->setBackgroundColorIndex(10);
        $this->assertEquals(10, $descriptor->getBackgroundColorIndex());

        $descriptor->setBackgroundColorIndex(11);
        $this->assertEquals(11, $descriptor->getBackgroundColorIndex());
    }

    public function testPixelAspectRatio()
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertEquals(0, $descriptor->getPixelAspectRatio());

        $descriptor->setPixelAspectRatio(10);
        $this->assertEquals(10, $descriptor->getPixelAspectRatio());

        $descriptor->setPixelAspectRatio(11);
        $this->assertEquals(11, $descriptor->getPixelAspectRatio());
    }

    public function testBitsPerPixel()
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertEquals(8, $descriptor->getBitsPerPixel());

        $descriptor->setBitsPerPixel(1);
        $this->assertEquals(1, $descriptor->getBitsPerPixel());

        $descriptor->setBitsPerPixel(2);
        $this->assertEquals(2, $descriptor->getBitsPerPixel());
    }

    public function testEncode()
    {
        // width: 10
        // height: 10
        // globalColorTableExistance: false
        // colorResolution: 8 bits/pixel
        // globalColorTableSorted: false
        // globalColorTableSize: 0
        // BackgroundColorIndex: 0
        // PixelAspectRatio: 0
        $descriptor = new LogicalScreenDescriptor();
        $descriptor->setSize(10, 10);
        $this->assertEquals("\x0A\x00\x0A\x00\x70\x00\x00", $descriptor->encode());

        // width: 300
        // height: 200
        // globalColorTableExistance: true
        // colorResolution: 8 bits/pixel
        // globalColorTableSorted: true
        // globalColorTableSize: 7
        // BackgroundColorIndex: 128
        // PixelAspectRatio: 0
        $descriptor = new LogicalScreenDescriptor();
        $descriptor->setSize(300, 200);
        $descriptor->setGlobalColorTableExistance(true);
        $descriptor->setGlobalColorTableSorted(true);
        $descriptor->setGlobalColorTableSize(7);
        $descriptor->setBackgroundColorIndex(128);
        $this->assertEquals("\x2c\x01\xc8\x00\xff\x80\x00", $descriptor->encode());
    }

    public function testDecode()
    {
        // width: 300
        // height: 200
        // globalColorTableExistance: true
        // colorResolution: 8 bits/pixel
        // globalColorTableSorted: true
        // globalColorTableSize: 7
        // BackgroundColorIndex: 128
        // PixelAspectRatio: 0
        $source = "\x2c\x01\xc8\x00\xff\x80\x00";
        $descriptor = LogicalScreenDescriptor::decode($this->getTestHandle($source));
        $this->assertInstanceOf(LogicalScreenDescriptor::class, $descriptor);
        $this->assertEquals(300, $descriptor->getWidth());
        $this->assertEquals(200, $descriptor->getHeight());
        $this->assertTrue($descriptor->getGlobalColorTableExistance());
        $this->assertEquals(8, $descriptor->getBitsPerPixel());
        $this->assertTrue($descriptor->getGlobalColorTableSorted());
        $this->assertEquals(7, $descriptor->getGlobalColorTableSize());
        $this->assertEquals(128, $descriptor->getBackgroundColorIndex());
        $this->assertEquals(0, $descriptor->getPixelAspectRatio());
    }
}
