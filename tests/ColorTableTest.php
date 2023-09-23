<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Color;
use Intervention\Gif\ColorTable;

class ColorTableTest extends BaseTestCase
{
    public function testConstructor()
    {
        $table = new ColorTable([
            new Color(0, 0, 0),
            new Color(255, 255, 255),
            new Color(255, 255, 255),
        ]);

        $this->assertInstanceOf(ColorTable::class, $table);
        $this->assertEquals(3, $table->countColors());
    }

    public function testGetColors()
    {
        $table = new ColorTable();
        $table->addRgb(0, 0, 0);
        $table->addRgb(0, 255, 0);

        $this->assertIsArray($table->getColors());
        $this->assertCount(2, $table->getColors());

        foreach ($table->getColors() as $key => $value) {
            $this->assertIsNumeric($key);
        }
    }

    public function testSetColors()
    {
        $table = new ColorTable();
        $this->assertEquals(0, $table->countColors());
        $table->setColors([
            new Color(0, 0, 0),
            new Color(255, 255, 255),
            new Color(255, 255, 255),
        ]);

        $this->assertEquals(3, $table->countColors());
    }

    public function testAddRgb()
    {
        $table = new ColorTable();
        $result = $table->addRgb(255, 255, 255);
        $this->assertInstanceOf(ColorTable::class, $result);
    }

    public function testCountColors()
    {
        $table = new ColorTable();
        $table->addRgb(0, 255, 255);
        $table->addRgb(255, 0, 255);
        $table->addRgb(255, 255, 0);
        $this->assertEquals(3, $table->countColors());
    }

    public function testHasColors()
    {
        $table = new ColorTable();
        $this->assertFalse($table->hasColors());
        $table->addRgb(0, 0, 0);
        $this->assertTrue($table->hasColors());
    }

    public function testEmpty()
    {
        $table = new ColorTable();
        $table->addRgb(0, 0, 0);
        $this->assertTrue($table->hasColors());
        $table->empty();
        $this->assertFalse($table->hasColors());
    }

    public function testGetLogicalSize()
    {
        $table = new ColorTable();
        $this->assertEquals(0, $table->getLogicalSize());
        $table->addRgb(0, 0, 0);
        $table->addRgb(255, 0, 0);
        $table->addRgb(255, 255, 0);
        $table->addRgb(255, 255, 255);
        $this->assertEquals(1, $table->getLogicalSize());
    }

    public function testGetByteSize()
    {
        $table = new ColorTable();
        $this->assertEquals(0, $table->getByteSize());

        $table->addRgb(0, 0, 0);
        $table->addRgb(255, 0, 0);
        $table->addRgb(255, 255, 0);
        $table->addRgb(255, 255, 255);

        $this->assertEquals(12, $table->getByteSize());
    }

    public function testEncode()
    {
        $table = new ColorTable([
            new Color(0, 0, 0),
            new Color(255, 0, 0),
            new Color(255, 255, 0),
            new Color(255, 255, 255),
        ]);

        $result = "\x00\x00\x00\xff\x00\x00\xff\xff\x00\xff\xff\xff";
        $this->assertEquals($result, $table->encode());
    }

    public function testDecode()
    {
        $source = "\x00\x00\x00\xff\x00\x00\xff\xff\x00\xff\xff\xff";
        $table = ColorTable::decode($this->getTestHandle($source), function ($decoder) {
            $decoder->setLength(12);
        });

        $this->assertInstanceOf(ColorTable::class, $table);
        $this->assertEquals(4, $table->countColors());
    }
}
