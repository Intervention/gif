<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Contracts\GraphicRenderingBlock;
use Intervention\Gif\GraphicBlock;
use Intervention\Gif\GraphicControlExtension;
use Intervention\Gif\PlainTextExtension;
use Intervention\Gif\TableBasedImage;

class GraphicBlockTest extends BaseTestCase
{
    public function testSetGetGraphicControlExtension()
    {
        $block = new GraphicBlock();
        $this->assertNull($block->getGraphicControlExtension());

        $block->setGraphicControlExtension(new GraphicControlExtension());
        $this->assertInstanceOf(GraphicControlExtension::class, $block->getGraphicControlExtension());
    }

    public function testSetGetGraphicRenderingBlock()
    {
        $block = new GraphicBlock();
        $this->assertInstanceOf(GraphicRenderingBlock::class, $block->getGraphicRenderingBlock());
        $block->setGraphicRenderingBlock(new TableBasedImage());
        $this->assertInstanceOf(TableBasedImage::class, $block->getGraphicRenderingBlock());
        $block->setGraphicRenderingBlock(new PlainTextExtension());
        $this->assertInstanceOf(PlainTextExtension::class, $block->getGraphicRenderingBlock());
    }

    public function testEncode()
    {
        $block = new GraphicBlock();
        $block->setGraphicControlExtension($this->getTestGraphicControlExtension());
        $block->setGraphicRenderingBlock($this->getTestTableBasedImage());
        $this->assertEquals(self::GRAPHIC_CONTROL_EXTENSION_SAMPLE.self::TABLE_BASED_IMAGE_SAMPLE, $block->encode());
    }

    public function testDecoder()
    {
        $sources = [
            // graphic control extension + imagedescriptor + imagedata
            "\x21\xF9\x04\x00\x00\x00\x00\x00\x2C\x00\x00\x00\x00\x0A\x00\x0A\x00\x00\x02\x16\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01\x00",

            // graphic control extension + plaintextextension
            // "\x21\xF9\x04\x00\x00\x00\x00\x00\x21\x01\x0C\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x03\x66\x6f\x6f\x03\x62\x61\x72\x00",
            
            // graphic control extension + imagedescriptor + localcolortable + imagedata
            "\x21\xF9\x04\x00\x00\x00\x00\x00\x2C\x00\x00\x00\x00\x0A\x00\x0A\x00\x81\xFF\xFF\xFF\xFF\x00\x00\x00\x00\xFF\x00\x00\x00\x02\x16\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01\x00",
            
            // imagedescriptor + localcolortable + imagedata
            "\x2C\x00\x00\x00\x00\x0A\x00\x0A\x00\x81\xFF\xFF\xFF\xFF\x00\x00\x00\x00\xFF\x00\x00\x00\x02\x16\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01\x00",
            
            // imagedescriptor + imagedata
            "\x2C\x00\x00\x00\x00\x0A\x00\x0A\x00\x00\x02\x16\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01\x00",

            // plaintextextension
            "\x21\x01\x0C\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x03\x66\x6f\x6f\x03\x62\x61\x72\x00",
        ];

        foreach ($sources as $source) {
            $block = GraphicBlock::decode($this->getTestHandle($source));
            $this->assertInstanceOf(GraphicBlock::class, $block);
        }
    }
}
