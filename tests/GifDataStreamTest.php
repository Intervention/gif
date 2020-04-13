<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\CommentExtension;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\GraphicBlock;
use Intervention\Gif\GraphicControlExtension;
use Intervention\Gif\Header;
use Intervention\Gif\LogicalScreen;
use Intervention\Gif\TableBasedImage;
use PHPUnit\Framework\TestCase;

class GifDataStreamTest extends TestCase
{
    private function getGifDataStream()
    {
        $gif = new GifDataStream;
        $gif->addData(new ApplicationExtension);
        $gif->addData((new CommentExtension)->addComment('foobar'));
        $gif->addData((new GraphicBlock)->setGraphicControlExtension(new GraphicControlExtension));

        return $gif;
    }

    public function testSetGetHeader()
    {
        $gif = new GifDataStream;
        $gif->setHeader(new Header);
        $this->assertInstanceOf(Header::class, $gif->getHeader());
    }

    public function testSetGetLogicalScreen()
    {
        $gif = new GifDataStream;
        $gif->setLogicalScreen(new LogicalScreen);
        $this->assertInstanceOf(LogicalScreen::class, $gif->getLogicalScreen());
    }

    public function testSetGetData()
    {
        $gif = new GifDataStream;
        $this->assertIsArray($gif->getData());
        $this->assertCount(0, $gif->getData());
        $gif->addData(new GraphicBlock);
        $gif->addData(new GraphicBlock);
        $this->assertCount(2, $gif->getData());
    }

    public function testEncode()
    {
        $gif = $this->getGifDataStream();

        $this->assertEquals("\x47\x49\x46\x38\x37\x61\x00\x00\x00\x00\x70\x00\x00\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\x00\x00\x00\x21\xfe\x06\x66\x6f\x6f\x62\x61\x72\x00\x21\xf9\x04\x00\x00\x00\x00\x00\x2c\x00\x00\x00\x00\x00\x00\x00\x00\x00\x3b", $gif->encode());
    }

    public function testDecode()
    {
        $source = file_get_contents(__DIR__.'/images/animation.gif');
        $gif = (new GifDataStream)->decode($source);
        $this->assertInstanceOf(GifDataStream::class, $gif);
        $this->assertEquals(20, $gif->getLogicalScreen()->getDescriptor()->getWidth());
        $this->assertEquals(15, $gif->getLogicalScreen()->getDescriptor()->getHeight());
    }
}
