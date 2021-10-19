<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Decoder;
use Intervention\Gif\GifDataStream;

class DecoderTest extends BaseTestCase
{
    public function testDecodeFromPath()
    {
        $decoded = Decoder::decode(__DIR__ . '/images/animation1.gif');
        $this->assertInstanceOf(GifDataStream::class, $decoded);
    }

    public function testDecodeFromData()
    {
        $decoded = Decoder::decode(file_get_contents(__DIR__ . '/images/animation1.gif'));
        $this->assertInstanceOf(GifDataStream::class, $decoded);
    }
}
