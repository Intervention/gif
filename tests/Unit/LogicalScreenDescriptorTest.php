<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\LogicalScreenDescriptor;
use Intervention\Gif\Tests\BaseTestCase;

final class LogicalScreenDescriptorTest extends BaseTestCase
{
    public function testSetGetSize(): void
    {
        $descriptor = new LogicalScreenDescriptor();
        $result = $descriptor->setSize(300, 200);
        $this->assertEquals(300, $descriptor->width());
        $this->assertEquals(200, $descriptor->height());
        $this->assertInstanceOf(LogicalScreenDescriptor::class, $result);
    }

    public function testGlobalColorTableExistanceFlag(): void
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertFalse($descriptor->globalColorTableExistance());

        $descriptor->setGlobalColorTableExistance();
        $this->assertTrue($descriptor->globalColorTableExistance());

        $descriptor->setGlobalColorTableExistance(false);
        $this->assertFalse($descriptor->globalColorTableExistance());
    }

    public function testGlobalColorTableSortFlag(): void
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertFalse($descriptor->globalColorTableSorted());

        $descriptor->setGlobalColorTableSorted();
        $this->assertTrue($descriptor->globalColorTableSorted());

        $descriptor->setGlobalColorTableSorted(false);
        $this->assertFalse($descriptor->globalColorTableSorted());
    }

    public function testGlobalColorTableSize(): void
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertEquals(0, $descriptor->globalColorTableSize());

        $descriptor->setGlobalColorTableSize(7);
        $this->assertEquals(7, $descriptor->globalColorTableSize());

        $descriptor->setGlobalColorTableSize(2);
        $this->assertEquals(2, $descriptor->globalColorTableSize());
    }

    public function testGlobalColorTableByteSize(): void
    {
        $descriptor = new LogicalScreenDescriptor(); // default: 0
        $this->assertEquals(6, $descriptor->globalColorTableByteSize());

        $descriptor->setGlobalColorTableSize(7);
        $this->assertEquals(768, $descriptor->globalColorTableByteSize());

        $descriptor->setGlobalColorTableSize(2);
        $this->assertEquals(24, $descriptor->globalColorTableByteSize());
    }

    public function testBackgroundColorIndex(): void
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertEquals(0, $descriptor->backgroundColorIndex());

        $descriptor->setBackgroundColorIndex(10);
        $this->assertEquals(10, $descriptor->backgroundColorIndex());

        $descriptor->setBackgroundColorIndex(11);
        $this->assertEquals(11, $descriptor->backgroundColorIndex());
    }

    public function testPixelAspectRatio(): void
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertEquals(0, $descriptor->pixelAspectRatio());

        $descriptor->setPixelAspectRatio(10);
        $this->assertEquals(10, $descriptor->pixelAspectRatio());

        $descriptor->setPixelAspectRatio(11);
        $this->assertEquals(11, $descriptor->pixelAspectRatio());
    }

    public function testBitsPerPixel(): void
    {
        $descriptor = new LogicalScreenDescriptor();
        $this->assertEquals(8, $descriptor->bitsPerPixel());

        $descriptor->setBitsPerPixel(1);
        $this->assertEquals(1, $descriptor->bitsPerPixel());

        $descriptor->setBitsPerPixel(2);
        $this->assertEquals(2, $descriptor->bitsPerPixel());
    }

    public function testEncode(): void
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

    public function testDecode(): void
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
        $descriptor = LogicalScreenDescriptor::decode($this->testHandle($source));
        $this->assertInstanceOf(LogicalScreenDescriptor::class, $descriptor);
        $this->assertEquals(300, $descriptor->width());
        $this->assertEquals(200, $descriptor->height());
        $this->assertTrue($descriptor->globalColorTableExistance());
        $this->assertEquals(8, $descriptor->bitsPerPixel());
        $this->assertTrue($descriptor->globalColorTableSorted());
        $this->assertEquals(7, $descriptor->globalColorTableSize());
        $this->assertEquals(128, $descriptor->backgroundColorIndex());
        $this->assertEquals(0, $descriptor->pixelAspectRatio());
    }
}
