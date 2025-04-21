<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Decoders\AbstractDecoder;
use Intervention\Gif\Tests\BaseTestCase;

final class AbstractDecoderTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $handle = $this->testHandle('foobarbaz');
        $decoder = $this->decoder($handle, 12);
        $this->assertEquals(12, $decoder->length());
    }

    public function testSetHandle(): void
    {
        $handle = $this->testHandle('foobarbaz');
        $decoder = $this->decoder($handle);
        $result = $decoder->setHandle($handle);
        $this->assertInstanceOf(AbstractDecoder::class, $result);
    }

    public function testSetGetLength(): void
    {
        $decoder = $this->decoder($this->testHandle('foobarbaz'));
        $this->assertNull($decoder->length());
        $decoder->setLength(1);
        $this->assertEquals(1, $decoder->length());
    }

    private function decoder(mixed $handle, ?int $length = null): AbstractDecoder
    {
        return new class ($handle, $length) extends AbstractDecoder
        {
            /**
             * Decode current source
             *
             * @return mixed
             */
            public function decode(): mixed
            {
                return null;
            }
        };
    }
}
