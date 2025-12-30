<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\Color;
use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Tests\BaseTestCase;

final class ColorTableTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $table = new ColorTable([
            new Color(0, 0, 0),
            new Color(255, 255, 255),
            new Color(255, 255, 255),
        ]);

        $this->assertInstanceOf(ColorTable::class, $table);
        $this->assertEquals(3, $table->countColors());
    }

    public function testGetColors(): void
    {
        $table = new ColorTable();
        $table->addRgb(0, 0, 0);
        $table->addRgb(0, 255, 0);

        $this->assertIsArray($table->colors());
        $this->assertCount(2, $table->colors());

        foreach (array_keys($table->colors()) as $key) {
            $this->assertIsNumeric($key);
        }
    }

    public function testSetColors(): void
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

    public function testAddRgb(): void
    {
        $table = new ColorTable();
        $result = $table->addRgb(255, 255, 255);
        $this->assertInstanceOf(ColorTable::class, $result);
    }

    public function testCountColors(): void
    {
        $table = new ColorTable();
        $table->addRgb(0, 255, 255);
        $table->addRgb(255, 0, 255);
        $table->addRgb(255, 255, 0);
        $this->assertEquals(3, $table->countColors());
    }

    public function testHasColors(): void
    {
        $table = new ColorTable();
        $this->assertFalse($table->hasColors());
        $table->addRgb(0, 0, 0);
        $this->assertTrue($table->hasColors());
    }

    public function testEmpty(): void
    {
        $table = new ColorTable();
        $table->addRgb(0, 0, 0);
        $this->assertTrue($table->hasColors());
        $table->empty();
        $this->assertFalse($table->hasColors());
    }

    public function testGetLogicalSize(): void
    {
        $table = new ColorTable();
        $this->assertEquals(0, $table->logicalSize());
        $table->addRgb(0, 0, 0);
        $table->addRgb(255, 0, 0);
        $table->addRgb(255, 255, 0);
        $table->addRgb(255, 255, 255);
        $this->assertEquals(1, $table->logicalSize());
    }

    public function testGetByteSize(): void
    {
        $table = new ColorTable();
        $this->assertEquals(0, $table->byteSize());

        $table->addRgb(0, 0, 0);
        $table->addRgb(255, 0, 0);
        $table->addRgb(255, 255, 0);
        $table->addRgb(255, 255, 255);

        $this->assertEquals(12, $table->byteSize());
    }

    public function testEncode(): void
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

    public function testDecode(): void
    {
        $source = "\x00\x00\x00\xff\x00\x00\xff\xff\x00\xff\xff\xff";
        $table = ColorTable::decode($this->filePointer($source), 12);

        $this->assertInstanceOf(ColorTable::class, $table);
        $this->assertEquals(4, $table->countColors());
    }
}
