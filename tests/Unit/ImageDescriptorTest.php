<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\ImageDescriptor;
use Intervention\Gif\Tests\BaseTestCase;

final class ImageDescriptorTest extends BaseTestCase
{
    public function testSetGetSize(): void
    {
        $desc = new ImageDescriptor();
        $this->assertEquals(0, $desc->width());
        $this->assertEquals(0, $desc->height());

        $desc->setSize(300, 200);
        $this->assertEquals(300, $desc->width());
        $this->assertEquals(200, $desc->height());
    }

    public function testSetGetPosition(): void
    {
        $desc = new ImageDescriptor();
        $this->assertEquals(0, $desc->top());
        $this->assertEquals(0, $desc->left());

        $desc->setPosition(300, 200);
        $this->assertEquals(300, $desc->left());
        $this->assertEquals(200, $desc->top());
    }

    public function testSetGetInterlaced(): void
    {
        $desc = new ImageDescriptor();
        $this->assertFalse($desc->isInterlaced());

        $desc->setInterlaced();
        $this->assertTrue($desc->isInterlaced());

        $desc->setInterlaced(false);
        $this->assertFalse($desc->isInterlaced());
    }

    public function testLocalColorTableExistanceFlag(): void
    {
        $descriptor = new ImageDescriptor();
        $this->assertFalse($descriptor->localColorTableExistance());

        $descriptor->setLocalColorTableExistance();
        $this->assertTrue($descriptor->localColorTableExistance());

        $descriptor->setLocalColorTableExistance(false);
        $this->assertFalse($descriptor->localColorTableExistance());
    }

    public function testLocalColorTableSortFlag(): void
    {
        $descriptor = new ImageDescriptor();
        $this->assertFalse($descriptor->localColorTableSorted());

        $descriptor->setLocalColorTableSorted();
        $this->assertTrue($descriptor->localColorTableSorted());

        $descriptor->setLocalColorTableSorted(false);
        $this->assertFalse($descriptor->localColorTableSorted());
    }

    public function testLocalColorTableSize(): void
    {
        $descriptor = new ImageDescriptor();
        $this->assertEquals(0, $descriptor->localColorTableSize());

        $descriptor->setLocalColorTableSize(7);
        $this->assertEquals(7, $descriptor->localColorTableSize());

        $descriptor->setLocalColorTableSize(2);
        $this->assertEquals(2, $descriptor->localColorTableSize());
    }

    public function testEncode(): void
    {
        // width: 10
        // height: 10
        // left: 10
        // top: 10
        // localColorTableExistance: false
        // Interlaced: false
        // localColorTableSorted: false
        // localColorTableSize: 0
        $descriptor = new ImageDescriptor();
        $descriptor->setSize(10, 10);
        $descriptor->setPosition(10, 10);
        $this->assertEquals("\x2C\x0A\x00\x0A\x00\x0A\x00\x0A\x00\x00", $descriptor->encode());

        // width: 300
        // height: 200
        // left: 1
        // top: 5
        // localColorTableExistance: true
        // Interlaced: true
        // localColorTableSorted: false
        // localColorTableSize: 4
        $descriptor = new ImageDescriptor();
        $descriptor->setSize(300, 200);
        $descriptor->setPosition(1, 5);
        $descriptor->setLocalColorTableExistance();
        $descriptor->setInterlaced();
        $descriptor->setLocalColorTableSorted(false);
        $descriptor->setLocalColorTableSize(4);
        $this->assertEquals("\x2C\x01\x00\x05\x00\x2c\x01\xc8\x00\xc4", $descriptor->encode());
    }

    public function testDecode(): void
    {
        // width: 300
        // height: 200
        // top: 0
        // left: 5
        // localColorTableExistance: true
        // Interlaced: true
        // localColorTableSorted: false
        // localColorTableSize: 4
        $source = "\x2C\x05\x00\x00\x00\x2c\x01\xc8\x00\xc4";
        $descriptor = ImageDescriptor::decode($this->testHandle($source));
        $this->assertInstanceOf(ImageDescriptor::class, $descriptor);
        $this->assertEquals(300, $descriptor->width());
        $this->assertEquals(200, $descriptor->height());
        $this->assertEquals(0, $descriptor->top());
        $this->assertEquals(5, $descriptor->left());
        $this->assertTrue($descriptor->localColorTableExistance());
        $this->assertFalse($descriptor->localColorTableSorted());
        $this->assertEquals(4, $descriptor->localColorTableSize());
        $this->assertTrue($descriptor->isInterlaced());
    }
}
