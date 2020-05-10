<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ColorTable;
use Intervention\Gif\LogicalScreen;
use Intervention\Gif\LogicalScreenDescriptor;

class LogicalScreenTest extends BaseTestCase
{
    public function testSetGetDescriptor()
    {
        $screen = new LogicalScreen();
        $screen->setDescriptor(new LogicalScreenDescriptor());
        $this->assertInstanceOf(LogicalScreenDescriptor::class, $screen->getDescriptor());
    }

    public function testSetGetColortable()
    {
        $screen = new LogicalScreen();
        $screen->setColorTable(new ColorTable());
        $this->assertInstanceOf(ColorTable::class, $screen->getColorTable());
    }

    public function testEncode()
    {
        $screen = new LogicalScreen();
        $screen->setDescriptor($this->getTestLogicalScreenDescriptor());
        $screen->setColorTable($this->getTestColorTable());
        $result = "\x51\x00\x16\x00\xf1\x00\x00\x00\x00\x00\xff\x00\x00\x00\xff\x00\x00\x00\xff";
        $this->assertEquals($result, $screen->encode());
    }

    public function testDecode()
    {
        $source = "\x14\x00\x0F\x00\xF4\x07\x00\x39\x4B\x63\xFF\xA6\x01\x42\x4F\x5F\x45\x51\x5D\x6D\x63\x49\xE6\x9B\x0D\x6A\x61\x4B\xB9\x86\x24\x3C\x4C\x61\x4B\x53\x5A\x9E\x79\x31\x98\x77\x34\xA8\x7E\x2C\xF6\xA2\x05\xF3\xA0\x07\x52\x56\x57\xCB\x8E\x1B\xCE\x90\x1A\x7D\x6A\x42\xFC\xA5\x03\x82\x6D\x3F\x8F\x73\x39\xF2\xA0\x08\xED\x9E\x0A\xF8\xA3\x04\x77\x68\x44\xD0\x90\x18\x72\x65\x47\x40\x4E\x60\x93\x74\x36\xC1\x89\x20\xC6\x8C\x1D";
        $screen = LogicalScreen::decode($this->getTestHandle($source));
        $this->assertInstanceOf(LogicalScreen::class, $screen);
        $this->assertInstanceOf(LogicalScreenDescriptor::class, $screen->getDescriptor());
        $this->assertInstanceOf(ColorTable::class, $screen->getColorTable());
    }
}
