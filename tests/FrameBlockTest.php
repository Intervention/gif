<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\ColorTable;
use Intervention\Gif\FrameBlock;
use Intervention\Gif\GraphicControlExtension;
use Intervention\Gif\ImageData;
use Intervention\Gif\DataSubBlock;
use Intervention\Gif\ImageDescriptor;
use Intervention\Gif\NetscapeApplicationExtension;
use Intervention\Gif\PlainTextExtension;

class FrameBlockTest extends BaseTestCase
{
    public function testEncode(): void
    {
        $frame = new FrameBlock();
        $frame->setImageDescriptor(
            (new ImageDescriptor())
                ->setSize(10, 10)
                ->setPosition(10, 10)
        );
        $frame->setImageData(
            (new ImageData())
                ->setLzwMinCodeSize(5)
                ->addBlock(new DataSubBlock("\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A\xDF\x78\xAE\xDF\x4F\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02"))
                ->addBlock(new DataSubBlock("\x01\x01\x01\x01"))
                ->addBlock(new DataSubBlock("\x01\x01\x01"))
        );

        $result = implode('', [
            // image descriptor
            "\x2C\x0A\x00\x0A\x00\x0A\x00\x0A\x00\x00",
            // imagedata
            "\x05\x24\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A\xDF\x78\xAE\xDF\x4F\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02\x04\x01\x01\x01\x01\x03\x01\x01\x01\x00",
        ]);
        $this->assertEquals($result, $frame->encode());
    }

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
