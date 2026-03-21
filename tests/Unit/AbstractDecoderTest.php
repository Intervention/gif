<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\Blocks\Header;
use Intervention\Gif\Decoders\AbstractDecoder;
use Intervention\Gif\Tests\BaseTestCase;

final class AbstractDecoderTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $stream = $this->stream('foobarbaz');
        $decoder = $this->decoder($stream, 12);
        $this->assertEquals(12, $decoder->length());
    }

    public function testSetStream(): void
    {
        $stream = $this->stream('foobarbaz');
        $decoder = $this->decoder($stream);
        $result = $decoder->setStream($stream);
        $this->assertInstanceOf(AbstractDecoder::class, $result);
    }

    public function testSetGetLength(): void
    {
        $decoder = $this->decoder($this->stream('foobarbaz'));
        $this->assertNull($decoder->length());
        $decoder->setLength(1);
        $this->assertEquals(1, $decoder->length());
    }

    private function decoder(mixed $stream, ?int $length = null): AbstractDecoder
    {
        return new class ($stream, $length) extends AbstractDecoder
        {
            /**
             * Decode current source
             */
            public function decode(): AbstractEntity
            {
                return new Header();
            }
        };
    }
}
