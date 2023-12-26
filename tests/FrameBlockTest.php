<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\ColorTable;
use Intervention\Gif\FrameBlock;
use Intervention\Gif\GraphicControlExtension;
use Intervention\Gif\ImageData;
use Intervention\Gif\ImageDescriptor;
use Intervention\Gif\NetscapeApplicationExtension;
use Intervention\Gif\PlainTextExtension;

class FrameBlockTest extends BaseTestCase
{
    public function testAddGetNetscapeExtension(): void
    {
        $frame = new FrameBlock();
        $this->assertNull($frame->getNetscapeExtension());

        $frame->addApplicationExtension(new ApplicationExtension());
        $this->assertNull($frame->getNetscapeExtension());

        $frame->addApplicationExtension(new NetscapeApplicationExtension());
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $frame->getNetscapeExtension());
    }

    public function testSetGetGraphicControlExtension(): void
    {
        $frame = new FrameBlock();
        $this->assertNull($frame->getGraphicControlExtension());
        $frame->setGraphicControlExtension(new GraphicControlExtension());
        $this->assertInstanceOf(GraphicControlExtension::class, $frame->getGraphicControlExtension());
    }

    public function testSetGetImageDescriptor(): void
    {
        $frame = new FrameBlock();
        $frame->setImageDescriptor(new ImageDescriptor());
        $this->assertInstanceOf(ImageDescriptor::class, $frame->getImageDescriptor());
    }

    public function testSetGetImageData(): void
    {
        $frame = new FrameBlock();
        $frame->setImageData(new ImageData());
        $this->assertInstanceOf(ImageData::class, $frame->getImageData());
    }

    public function testSetGetColorTable(): void
    {
        $frame = new FrameBlock();
        $this->assertNull($frame->getColorTable());
        $frame->setColorTable(new ColorTable());
        $this->assertInstanceOf(ColorTable::class, $frame->getColorTable());
    }

    public function testSetGetPlainTextExtension(): void
    {
        $frame = new FrameBlock();
        $this->assertNull($frame->getPlainTextExtension());
        $frame->setPlainTextExtension(new PlainTextExtension());
        $this->assertInstanceOf(PlainTextExtension::class, $frame->getPlainTextExtension());
    }
}
