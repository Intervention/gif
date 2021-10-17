<?php

namespace Intervention\Gif\Test;

use GdImage;
use Intervention\Gif\Builder;
use Intervention\Gif\Decoder;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Splitter;

class SplitterTest extends BaseTestCase
{
    public function testSplit()
    {
        $decoded = Decoder::decode(__DIR__ . '/images/animation1.gif');
        $splitter = Splitter::create($decoded)->split();
        $this->assertCount(8, $splitter->getFrames());
        foreach ($splitter->getFrames() as $key => $gif) {
            $this->assertInstanceOf(GifDataStream::class, $gif);
            $this->assertEquals(20, $gif->getLogicalScreen()->getDescriptor()->getWidth());
            $this->assertEquals(15, $gif->getLogicalScreen()->getDescriptor()->getHeight());
            $this->assertInstanceOf(GifDataStream::class, Decoder::decode($gif->encode()));
        }

        foreach ($splitter->toResources() as $key => $gif) {
            $this->assertInstanceOf(GdImage::class, $gif);
            // imagegif($gif, __DIR__ . '/img_resource_' . $key . '.gif');
        }

        foreach ($splitter->coalesceToResources() as $key => $gif) {
            $this->assertInstanceOf(GdImage::class, $gif);
            // imagegif($gif, __DIR__ . '/img_coa_' . $key . '.gif');
        }
    }

    public function testGetDelays(): void
    {
        $decoded = Decoder::decode(__DIR__ . '/images/animation2.gif');
        $delays = Splitter::create($decoded)->split()->getDelays();
        $this->assertEquals($delays, array_fill(0, 6, 13));
    }
}
