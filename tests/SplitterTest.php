<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Builder;
use Intervention\Gif\Decoder;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Splitter;

class SplitterTest extends BaseTestCase
{
    public function testSplit()
    {
        $decoded = Decoder::decode(__DIR__ . '/images/animation.gif');
        $splitter = Splitter::create($decoded)->split();
        $this->assertCount(8, $splitter->toArray());
        foreach ($splitter->toArray() as $gif) {
            $this->assertInstanceOf(GifDataStream::class, $gif);
            $this->assertEquals(20, $gif->getLogicalScreen()->getDescriptor()->getWidth());
            $this->assertEquals(15, $gif->getLogicalScreen()->getDescriptor()->getHeight());
            $this->assertInstanceOf(GifDataStream::class, Decoder::decode($gif->encode()));
        }

        foreach ($splitter->toResources() as $gif) {
            $this->assertIsResource($gif);
        }

        foreach ($splitter->coalesceToResources() as $gif) {
            $this->assertIsResource($gif);
        }
    }
}
