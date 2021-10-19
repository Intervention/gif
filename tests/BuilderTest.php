<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Builder;
use Intervention\Gif\ColorTable;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\GraphicBlock;
use Intervention\Gif\GraphicControlExtension;
use Intervention\Gif\ImageDescriptor;
use Intervention\Gif\TableBasedImage;

class BuilderTest extends BaseTestCase
{
    public function testGetGifDataStream()
    {
        $builder = Builder::canvas(320, 240);
        $this->assertInstanceOf(GifDataStream::class, $builder->getGifDataStream());
    }

    public function testEncode()
    {
        $builder = Builder::canvas(320, 240);
        $this->assertMatchesRegularExpression('/^\x47\x49\x46\x38(\x37|\x39)\x61/', $builder->encode());
    }

    public function testCanvasDefaultLoops()
    {
        $builder = Builder::canvas(320, 240);
        $this->assertInstanceOf(Builder::class, $builder);
        $gif = $builder->getGifDataStream();
        $this->assertEquals(320, $gif->getLogicalScreen()->getDescriptor()->getWidth());
        $this->assertEquals(240, $gif->getLogicalScreen()->getDescriptor()->getHeight());
        $this->assertEquals(0, $gif->getMainApplicationExtension()->getLoops());
    }

    public function testCanvasOneLoops()
    {
        $builder = Builder::canvas(320, 240, 1);
        $gif = $builder->getGifDataStream();
        $this->assertNull($gif->getMainApplicationExtension());
    }

    public function testCanvasMultipleLoops()
    {
        $builder = Builder::canvas(320, 240, 10);
        $gif = $builder->getGifDataStream();
        $this->assertEquals(10, $gif->getMainApplicationExtension()->getLoops());
    }

    public function testAddFrame()
    {
        $builder = Builder::canvas(320, 240);
        $result = $builder->addFrame(__DIR__ . '/images/red.gif', 0.25, 1, 2);
        $this->assertInstanceOf(Builder::class, $result);
        $blocks = $result->getGifDataStream()->getGraphicBlocks();
        $this->assertEquals(25, $blocks[0]->getGraphicControlExtension()->getDelay());
        $this->assertEquals(1, $blocks[0]->getGraphicRenderingBlock()->getDescriptor()->getLeft());
        $this->assertEquals(2, $blocks[0]->getGraphicRenderingBlock()->getDescriptor()->getTop());
    }

    public function testBuilder(): void
    {
        $gd1 = imagecreatetruecolor(30, 20);
        imagefill($gd1, 0, 0, imagecolorallocate($gd1, 255, 0, 0));
        $gd2 = imagecreatetruecolor(30, 20);
        imagefill($gd2, 0, 0, imagecolorallocate($gd2, 0, 255, 0));
        $gd3 = imagecreatetruecolor(30, 20);
        imagefill($gd3, 0, 0, imagecolorallocate($gd3, 0, 0, 255));

        $builder = Builder::canvas(30, 20);
        $builder->addFrame($this->gdEncodeGif($gd1));
        $builder->addFrame($this->gdEncodeGif($gd2));
        $builder->addFrame($this->gdEncodeGif($gd3));

        $result = $builder->encode();
        // file_put_contents(__DIR__ . '/testxxx.gif', $result);

        $this->assertEquals(182, strlen($result));
    }

    protected function gdEncodeGif($gd): string
    {
        ob_start();
        imagegif($gd);
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
}
