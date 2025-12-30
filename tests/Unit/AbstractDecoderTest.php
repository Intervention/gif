<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Decoders\AbstractDecoder;
use Intervention\Gif\Tests\BaseTestCase;

final class AbstractDecoderTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $filePointer = $this->filePointer('foobarbaz');
        $decoder = $this->decoder($filePointer, 12);
        $this->assertEquals(12, $decoder->length());
    }

    public function testSetFilePointer(): void
    {
        $filePointer = $this->filePointer('foobarbaz');
        $decoder = $this->decoder($filePointer);
        $result = $decoder->setFilePointer($filePointer);
        $this->assertInstanceOf(AbstractDecoder::class, $result);
    }

    public function testSetGetLength(): void
    {
        $decoder = $this->decoder($this->filePointer('foobarbaz'));
        $this->assertNull($decoder->length());
        $decoder->setLength(1);
        $this->assertEquals(1, $decoder->length());
    }

    private function decoder(mixed $filePointer, ?int $length = null): AbstractDecoder
    {
        return new class ($filePointer, $length) extends AbstractDecoder
        {
            /**
             * Decode current source
             */
            public function decode(): mixed
            {
                return null;
            }
        };
    }
}
