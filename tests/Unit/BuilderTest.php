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
        $this->assertInstanceOf(GifDataStream::class, $builder->gifDataStream());
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
        $gif = $builder->gifDataStream();
        $this->assertEquals(320, $gif->logicalScreenDescriptor()->width());
        $this->assertEquals(240, $gif->logicalScreenDescriptor()->height());
    }

    public function testCanvasMultipleLoops(): void
    {
        $builder = Builder::canvas(320, 240);
        $builder->addFrame($this->testImagePath('red.gif'), 0.25, 1, 2);
        $builder->setLoops(10);
        $gif = $builder->gifDataStream();
        $this->assertEquals(10, $gif->mainApplicationExtension()->loops());
    }

    public function testAddFrame(): void
    {
        $builder = Builder::canvas(320, 240);
        $result = $builder->addFrame($this->testImagePath('red.gif'), 0.25, 1, 2);
        $this->assertInstanceOf(Builder::class, $result);
        $gif = $builder->gifDataStream();
        $this->assertEquals(25, $gif->firstFrame()->graphicControlExtension()->delay());
        $this->assertEquals(1, $gif->firstFrame()->imageDescriptor()->left());
        $this->assertEquals(2, $gif->firstFrame()->imageDescriptor()->top());
        $this->assertFalse($gif->firstFrame()->imageDescriptor()->isInterlaced());
    }

    public function testAddFrameInterlace(): void
    {
        $builder = Builder::canvas(320, 240);
        $result = $builder->addFrame($this->testImagePath('red.gif'), 0.25, 1, 2, true);
        $this->assertInstanceOf(Builder::class, $result);
        $gif = $builder->gifDataStream();
        $this->assertEquals(25, $gif->firstFrame()->graphicControlExtension()->delay());
        $this->assertEquals(1, $gif->firstFrame()->imageDescriptor()->left());
        $this->assertEquals(2, $gif->firstFrame()->imageDescriptor()->top());
        $this->assertTrue($gif->firstFrame()->imageDescriptor()->isInterlaced());
    }

    public function testAddFrameFromResource(): void
    {
        $pointer = fopen('php://temp', 'r+');
        fwrite($pointer, file_get_contents($this->testImagePath('animation1.gif')));
        $builder = Builder::canvas(320, 240);
        $result = $builder->addFrame($pointer);
        $this->assertInstanceOf(Builder::class, $result);
    }
}
