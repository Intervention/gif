<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Builder;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Tests\BaseTestCase;

final class BuilderTest extends BaseTestCase
{
    public function testGetGifDataStream(): void
    {
        $builder = Builder::canvas(320, 240);
        $this->assertInstanceOf(GifDataStream::class, $builder->getGifDataStream());
    }

    public function testEncode(): void
    {
        $builder = Builder::canvas(320, 240);
        $this->assertMatchesRegularExpression('/^\x47\x49\x46\x38(\x37|\x39)\x61/', $builder->encode());
    }

    public function testCanvas(): void
    {
        $builder = Builder::canvas(320, 240);
        $this->assertInstanceOf(Builder::class, $builder);
        $gif = $builder->getGifDataStream();
        $this->assertEquals(320, $gif->getLogicalScreenDescriptor()->getWidth());
        $this->assertEquals(240, $gif->getLogicalScreenDescriptor()->getHeight());
    }

    public function testCanvasMultipleLoops(): void
    {
        $builder = Builder::canvas(320, 240);
        $builder->addFrame($this->getTestImagePath('red.gif'), 0.25, 1, 2);
        $builder->setLoops(10);
        $gif = $builder->getGifDataStream();
        $this->assertEquals(10, $gif->getMainApplicationExtension()->getLoops());
    }

    public function testAddFrame(): void
    {
        $builder = Builder::canvas(320, 240);
        $result = $builder->addFrame($this->getTestImagePath('red.gif'), 0.25, 1, 2);
        $this->assertInstanceOf(Builder::class, $result);
        $gif = $builder->getGifDataStream();
        $this->assertEquals(25, $gif->getFirstFrame()->getGraphicControlExtension()->getDelay());
        $this->assertEquals(1, $gif->getFirstFrame()->getImageDescriptor()->getLeft());
        $this->assertEquals(2, $gif->getFirstFrame()->getImageDescriptor()->getTop());
        $this->assertFalse($gif->getFirstFrame()->getImageDescriptor()->isInterlaced());
    }

    public function testAddFrameInterlace(): void
    {
        $builder = Builder::canvas(320, 240);
        $result = $builder->addFrame($this->getTestImagePath('red.gif'), 0.25, 1, 2, true);
        $this->assertInstanceOf(Builder::class, $result);
        $gif = $builder->getGifDataStream();
        $this->assertEquals(25, $gif->getFirstFrame()->getGraphicControlExtension()->getDelay());
        $this->assertEquals(1, $gif->getFirstFrame()->getImageDescriptor()->getLeft());
        $this->assertEquals(2, $gif->getFirstFrame()->getImageDescriptor()->getTop());
        $this->assertTrue($gif->getFirstFrame()->getImageDescriptor()->isInterlaced());
    }

    public function testAddFrameFromResource(): void
    {
        $pointer = fopen('php://temp', 'r+');
        fwrite($pointer, file_get_contents($this->getTestImagePath('animation1.gif')));
        $builder = Builder::canvas(320, 240);
        $result = $builder->addFrame($pointer);
        $this->assertInstanceOf(Builder::class, $result);
    }
}
