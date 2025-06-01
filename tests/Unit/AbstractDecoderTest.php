<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Decoders\AbstractDecoder;
use Intervention\Gif\Tests\BaseTestCase;

final class AbstractDecoderTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $handle = $this->getTestHandle('foobarbaz');
        $decoder = $this->decoder($handle, 12);
        $this->assertEquals(12, $decoder->getLength());
    }

    public function testSetHandle(): void
    {
        $handle = $this->getTestHandle('foobarbaz');
        $decoder = $this->decoder($handle);
        $result = $decoder->setHandle($handle);
        $this->assertInstanceOf(AbstractDecoder::class, $result);
    }

    public function testSetGetLength(): void
    {
        $decoder = $this->decoder($this->getTestHandle('foobarbaz'));
        $this->assertNull($decoder->getLength());
        $decoder->setLength(1);
        $this->assertEquals(1, $decoder->getLength());
    }

    private function decoder(mixed $handle, ?int $length = null): AbstractDecoder
    {
        return new class ($handle, $length) extends AbstractDecoder
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
