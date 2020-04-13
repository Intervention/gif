<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ColorTable;
use Intervention\Gif\ImageData;
use Intervention\Gif\ImageDescriptor;
use Intervention\Gif\TableBasedImage;
use PHPUnit\Framework\TestCase;

class TableBasedImageTest extends TestCase
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
}
