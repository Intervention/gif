<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\ApplicationExtension;
use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Blocks\FrameBlock;
use Intervention\Gif\Blocks\GraphicControlExtension;
use Intervention\Gif\Blocks\ImageData;
use Intervention\Gif\Blocks\DataSubBlock;
use Intervention\Gif\Blocks\ImageDescriptor;
use Intervention\Gif\Blocks\NetscapeApplicationExtension;
use Intervention\Gif\Blocks\PlainTextExtension;
use Intervention\Gif\Tests\BaseTestCase;

final class FrameBlockTest extends BaseTestCase
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
                ->addBlock(new DataSubBlock("\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A" .
                    "\xDF\x78\xAE\xDF\x4F\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02"))
                ->addBlock(new DataSubBlock("\x01\x01\x01\x01"))
                ->addBlock(new DataSubBlock("\x01\x01\x01"))
        );

        $result = implode('', [
            // image descriptor
            "\x2C\x0A\x00\x0A\x00\x0A\x00\x0A\x00\x00",
            // imagedata
            "\x05\x24\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A\xDF\x78\xAE\xDF\x4F" .
            "\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02\x04\x01\x01\x01\x01\x03\x01\x01\x01\x00",
        ]);
        $this->assertEquals($result, $frame->encode());
    }

    public function testDecode(): void
    {
        $source = implode('', [
            // graphicControlExtension
            "\x21\xF9\x04\x0f\x96\x00\x01\x00",
            // NetscapeApplicationExtension
            "\x21\xFF\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x01\x00\x00",
            // applicationExtension
            "\x21\xff\x06\x66\x6F\x6F\x62\x61\x72\x03\x62\x61\x7A\x00",
            // comment extension
            "\x21\xFE\x09\x62\x6C\x75\x65\x62\x65\x72\x72\x79\x00",
            // comment extension
            "\x21\xFE\x09\x62\x6C\x75\x65\x62\x65\x72\x72\x79\x00",
            // plainTextExtension
            "\x21\x01\x0C\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x03\x66\x6f\x6f\x03\x62\x61\x72\x00",
            // image descriptor
            "\x2C\x0A\x00\x0A\x00\x0A\x00\x0A\x00\x00",
            // imagedata
            "\x05\x24\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A\xDF\x78\xAE\xDF\x4F" .
            "\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02\x04\x01\x01\x01\x01\x03\x01\x01\x01\x00",
        ]);

        $block = FrameBlock::decode($this->filePointer($source));
        $this->assertInstanceOf(FrameBlock::class, $block);
        $this->assertInstanceOf(GraphicControlExtension::class, $block->getGraphicControlExtension());
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $block->getNetscapeExtension());
        $this->assertInstanceOf(PlainTextExtension::class, $block->getPlainTextExtension());
        $this->assertCount(2, $block->getApplicationExtensions());
        $this->assertCount(2, $block->getCommentExtensions());
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
