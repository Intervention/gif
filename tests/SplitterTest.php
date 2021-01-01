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
        $gifs = Splitter::create($decoded)->split();
        $this->assertCount(8, $gifs);
        foreach ($gifs as $gif) {
            $this->assertInstanceOf(GifDataStream::class, $gif);
            $this->assertInstanceOf(GifDataStream::class, Decoder::decode($gif->encode()));
        }
    }
}
