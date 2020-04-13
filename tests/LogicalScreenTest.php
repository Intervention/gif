<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ColorTable;
use Intervention\Gif\LogicalScreen;
use Intervention\Gif\LogicalScreenDescriptor;
use PHPUnit\Framework\TestCase;

class LogicalScreenTest extends TestCase
{
    public function testSetGetDescriptor()
    {
        $screen = new LogicalScreen;
        $screen->setDescriptor(new LogicalScreenDescriptor);
        $this->assertInstanceOf(LogicalScreenDescriptor::class, $screen->getDescriptor());
    }

    public function testSetGetColortable()
    {
        $screen = new LogicalScreen;
        $screen->setColorTable(new ColorTable);
        $this->assertInstanceOf(ColorTable::class, $screen->getColorTable());
    }
}
