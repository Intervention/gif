<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use GdImage;
use Intervention\Gif\Decoder;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Splitter;
use Intervention\Gif\Tests\BaseTestCase;

final class SplitterTest extends BaseTestCase
{
    public function testSplit(): void
    {
        $decoded = Decoder::decode($this->testImagePath('animation1.gif'));
        $splitter = Splitter::create($decoded)->split();
        $this->assertCount(8, $splitter->frames());
        foreach ($splitter->frames() as $gif) {
            $this->assertInstanceOf(GifDataStream::class, $gif);
            $this->assertEquals(20, $gif->logicalScreenDescriptor()->width());
            $this->assertEquals(15, $gif->logicalScreenDescriptor()->height());
            $this->assertInstanceOf(GifDataStream::class, Decoder::decode($gif->encode()));
        }

        foreach ($splitter->toResources() as $gif) {
            $this->assertInstanceOf(GdImage::class, $gif);
        }

        foreach ($splitter->coalesceToResources() as $gif) {
            $this->assertInstanceOf(GdImage::class, $gif);
        }
    }

    public function testGetDelays(): void
    {
        $decoded = Decoder::decode($this->testImagePath('animation2.gif'));
        $delays = Splitter::create($decoded)->split()->delays();
        $this->assertEquals($delays, array_fill(0, 6, 13));
    }

    public function testSplitStatic(): void
    {
        $decoded = Decoder::decode($this->testImagePath('static.gif'));
        $splitter = Splitter::create($decoded)->split();
        $this->assertCount(1, $splitter->frames());
        foreach ($splitter->frames() as $gif) {
            $this->assertInstanceOf(GifDataStream::class, $gif);
            $this->assertEquals(16, $gif->logicalScreenDescriptor()->width());
            $this->assertEquals(10, $gif->logicalScreenDescriptor()->height());
            $this->assertInstanceOf(GifDataStream::class, Decoder::decode($gif->encode()));
        }

        foreach ($splitter->toResources() as $gif) {
            $this->assertInstanceOf(GdImage::class, $gif);
        }

        foreach ($splitter->coalesceToResources() as $gif) {
            $this->assertInstanceOf(GdImage::class, $gif);
        }
    }

    public function testGetDelaysStatic(): void
    {
        $decoded = Decoder::decode($this->testImagePath('static.gif'));
        $delays = Splitter::create($decoded)->split()->delays();
        $this->assertEquals($delays, [0]);
    }
}
