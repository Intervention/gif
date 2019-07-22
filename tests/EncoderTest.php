<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Decoded;
use Intervention\Gif\Encoder as Encoder;
use Intervention\Gif\Frame;
use PHPUnit\Framework\TestCase;

class EncoderTest extends TestCase
{
    public function testSetCanvas()
    {
        $encoder = new Encoder;
        $result = $encoder->setCanvas(300, 200);
        $this->assertInstanceOf(Encoder::class, $result);
        $this->assertEquals(300, $encoder->canvasWidth);
        $this->assertEquals(200, $encoder->canvasHeight);
    }

    public function testSetLoops()
    {
        $encoder = new Encoder;
        $result = $encoder->setLoops(6);
        $this->assertInstanceOf(Encoder::class, $result);
        $this->assertEquals(6, $encoder->loops);
    }

    public function testSetGlobalColorTable()
    {
        $encoder = new Encoder;
        $result = $encoder->setGlobalColorTable('foo');
        $this->assertInstanceOf(Encoder::class, $result);
        $this->assertEquals('foo', $encoder->globalColorTable);
    }

    public function testSetFrames()
    {
        $encoder = new Encoder;
        $frames = ['foo', 'bar', 'baz'];
        $encoder->setFrames($frames);
        $this->assertEquals($frames, $encoder->frames);
    }

    public function testSetBackgroundColorIndex()
    {
        $encoder = new Encoder;
        $result = $encoder->setBackgroundColorIndex('foo');
        $this->assertInstanceOf(Encoder::class, $result);
        $this->assertEquals('foo', $encoder->backgroundColorIndex);
    }

    public function testSetFromDecoded()
    {
        $encoder = new Encoder;
        $decoded = $this->createMock(Decoded::class);
        $decoded->method('getCanvasWidth')->willReturn(300);
        $decoded->method('getCanvasHeight')->willReturn(200);
        $decoded->method('getGlobalColorTable')->willReturn('global_color_table');
        $decoded->method('getBackgroundColorIndex')->willReturn('background_color_index');
        $decoded->method('getLoops')->willReturn(2);
        $decoded->method('getFrames')->willReturn(['frame1', 'frame2', 'frame3']);
        $encoder->setFromDecoded($decoded);
        $this->assertEquals(300, $encoder->canvasWidth);
        $this->assertEquals(200, $encoder->canvasHeight);
        $this->assertEquals('global_color_table', $encoder->globalColorTable);
        $this->assertEquals('background_color_index', $encoder->backgroundColorIndex);
        $this->assertEquals(2, $encoder->loops);
        $this->assertEquals(3, count($encoder->frames));
        $this->assertTrue($encoder->doesLoop());
    }

    public function testAddFrame()
    {
        $frame = $this->createMock(Frame::class);
        $encoder = new Encoder;
        $encoder->addFrame($frame);
        $encoder->addFrame($frame);
        $this->assertEquals(2, count($encoder->frames));
    }

    public function testCreateFrameFromGdResource()
    {
        $encoder = new Encoder;
        $resource = imagecreate(10, 10);
        $encoder->createFrameFromGdResource($resource, 10);
        $this->assertEquals(1, count($encoder->frames));
    }

    public function testIsAnimated()
    {
        $frame = $this->createMock(Frame::class);
        $encoder = new Encoder;
        $this->assertFalse($encoder->isAnimated());
        $encoder->addFrame($frame);
        $this->assertFalse($encoder->isAnimated());
        $encoder->addFrame($frame);
        $this->assertTrue($encoder->isAnimated());
    }

    public function testDoesLoop()
    {
        $encoder = new Encoder;
        $this->assertFalse($encoder->doesLoop());
        $encoder->setLoops(10);
        $this->assertTrue($encoder->doesLoop());
    }
}
