<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ColorTable;
use Intervention\Gif\ImageData;
use Intervention\Gif\ImageDescriptor;
use Intervention\Gif\TableBasedImage;

class TableBasedImageTest extends BaseTestCase
{
    public function testConstrutor()
    {
        $tbi = new TableBasedImage;
        $this->assertNotNull($tbi->getDescriptor());
        $this->assertNotNull($tbi->getData());
        $this->assertNull($tbi->getColorTable());
        $this->assertFalse($tbi->hasColorTable());
    }

    public function testDescriptor()
    {
        $tbi = new TableBasedImage;
        $tbi->setDescriptor(new ImageDescriptor);
        $this->assertInstanceOf(ImageDescriptor::class, $tbi->getDescriptor());
    }

    public function testData()
    {
        $tbi = new TableBasedImage;
        $tbi->setData(new ImageData);
        $this->assertInstanceOf(ImageData::class, $tbi->getData());
    }

    public function testColorTable()
    {
        $tbi = new TableBasedImage;
        $this->assertFalse($tbi->hasColorTable());
        $tbi->setColortable(new ColorTable);
        $this->assertInstanceOf(ColorTable::class, $tbi->getColorTable());
        $this->assertTrue($tbi->hasColorTable());
    }

    public function testDecode()
    {
        $source = "\x2C\x00\x00\x00\x00\x0A\x00\x0A\x00\xe1\xFF\xFF\xFF\xFF\x00\x00\x00\x00\xFF\x00\x00\x00\x02\x16\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01\x03\x09\x03\x01\x00";
        $tbi = TableBasedImage::decode($this->getTestHandle($source));
        $this->assertInstanceOf(TableBasedImage::class, $tbi);
        $this->assertInstanceOf(ImageDescriptor::class, $tbi->getDescriptor());
        $this->assertInstanceOf(ColorTable::class, $tbi->getColorTable());
        $this->assertInstanceOf(ImageData::class, $tbi->getData());
    }
}
