<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Builder;
use Intervention\Gif\GifDataStream;

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

    public function testCanvas()
    {
        $builder = Builder::canvas(320, 240);
        $this->assertInstanceOf(Builder::class, $builder);
        $gif = $builder->getGifDataStream();
        $this->assertEquals(320, $gif->getLogicalScreenDescriptor()->getWidth());
        $this->assertEquals(240, $gif->getLogicalScreenDescriptor()->getHeight());
    }

    public function testCanvasMultipleLoops()
    {
        $builder = Builder::canvas(320, 240);
        $builder->addFrame(__DIR__ . '/images/red.gif', 0.25, 1, 2);
        $builder->setLoops(10);
        $gif = $builder->getGifDataStream();
        $this->assertEquals(10, $gif->getMainApplicationExtension()->getLoops());
    }

    public function testAddFrame()
    {
        $builder = Builder::canvas(320, 240);
        $result = $builder->addFrame(__DIR__ . '/images/red.gif', 0.25, 1, 2);
        $this->assertInstanceOf(Builder::class, $result);
        $gif = $builder->getGifDataStream();
        $this->assertEquals(25, $gif->getFirstFrame()->getGraphicControlExtension()->getDelay());
        $this->assertEquals(1, $gif->getFirstFrame()->getImageDescriptor()->getLeft());
        $this->assertEquals(2, $gif->getFirstFrame()->getImageDescriptor()->getTop());
    }
}
