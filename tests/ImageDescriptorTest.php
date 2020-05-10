<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Color;
use Intervention\Gif\ColorTable;
use Intervention\Gif\ImageDescriptor;

class ImageDescriptorTest extends BaseTestCase
{
    public function testSetGetSize()
    {
        $desc = new ImageDescriptor();
        $this->assertEquals(0, $desc->getWidth());
        $this->assertEquals(0, $desc->getHeight());

        $desc->setSize(300, 200);
        $this->assertEquals(300, $desc->getWidth());
        $this->assertEquals(200, $desc->getHeight());
    }

    public function testSetGetPosition()
    {
        $desc = new ImageDescriptor();
        $this->assertEquals(0, $desc->getTop());
        $this->assertEquals(0, $desc->getLeft());

        $desc->setPosition(300, 200);
        $this->assertEquals(300, $desc->getLeft());
        $this->assertEquals(200, $desc->getTop());
    }

    public function testSetGetInterlaced()
    {
        $desc = new ImageDescriptor();
        $this->assertFalse($desc->isInterlaced());

        $desc->setInterlaced();
        $this->assertTrue($desc->isInterlaced());
        
        $desc->setInterlaced(false);
        $this->assertFalse($desc->isInterlaced());
    }

    public function testLocalColorTableExistanceFlag()
    {
        $descriptor = new ImageDescriptor();
        $this->assertFalse($descriptor->getLocalColorTableExistance());

        $descriptor->setLocalColorTableExistance();
        $this->assertTrue($descriptor->getLocalColorTableExistance());

        $descriptor->setLocalColorTableExistance(false);
        $this->assertFalse($descriptor->getLocalColorTableExistance());
    }

    public function testLocalColorTableSortFlag()
    {
        $descriptor = new ImageDescriptor();
        $this->assertFalse($descriptor->getLocalColorTableSorted());

        $descriptor->setLocalColorTableSorted();
        $this->assertTrue($descriptor->getLocalColorTableSorted());

        $descriptor->setLocalColorTableSorted(false);
        $this->assertFalse($descriptor->getLocalColorTableSorted());
    }

    public function testLocalColorTableSize()
    {
        $descriptor = new ImageDescriptor();
        $this->assertEquals(0, $descriptor->getLocalColorTableSize());

        $descriptor->setLocalColorTableSize(7);
        $this->assertEquals(7, $descriptor->getLocalColorTableSize());

        $descriptor->setLocalColorTableSize(2);
        $this->assertEquals(2, $descriptor->getLocalColorTableSize());
    }

    public function testEncode()
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

    public function testDecode()
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
        $descriptor = ImageDescriptor::decode($this->getTestHandle($source));
        $this->assertInstanceOf(ImageDescriptor::class, $descriptor);
        $this->assertEquals(300, $descriptor->getWidth());
        $this->assertEquals(200, $descriptor->getHeight());
        $this->assertEquals(0, $descriptor->getTop());
        $this->assertEquals(5, $descriptor->getLeft());
        $this->assertTrue($descriptor->getLocalColorTableExistance());
        $this->assertFalse($descriptor->getLocalColorTableSorted());
        $this->assertEquals(4, $descriptor->getLocalColorTableSize());
        $this->assertTrue($descriptor->isInterlaced());
    }
}
