<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Decoder;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Tests\BaseTestCase;

final class DecoderTest extends BaseTestCase
{
    public function testDecodeFromPath(): void
    {
        $decoded = Decoder::decode($this->getTestImagePath('animation1.gif'));
        $this->assertInstanceOf(GifDataStream::class, $decoded);
    }

    public function testDecodeFromData(): void
    {
        $decoded = Decoder::decode(file_get_contents($this->getTestImagePath('animation1.gif')));
        $this->assertInstanceOf(GifDataStream::class, $decoded);
    }
}
