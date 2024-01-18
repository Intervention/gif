<?php

namespace Intervention\Gif\Tests;

use Intervention\Gif\Decoders\AbstractDecoder;

class AbstractDecoderTest extends BaseTestCase
{
    public function testConstructor()
    {
        $handle = $this->getTestHandle('foobarbaz');
        $decoder = $this->getMockForAbstractClass(AbstractDecoder::class, [$handle, 12]);
        $this->assertEquals(12, $decoder->getLength());
    }

    public function testSetHandle()
    {
        $handle = $this->getTestHandle('foobarbaz');
        $decoder = $this->getMockForAbstractClass(AbstractDecoder::class, [$handle]);
        $result = $decoder->setHandle($handle);
        $this->assertInstanceOf(AbstractDecoder::class, $result);
    }

    public function testSetGetLength()
    {
        $handle = $this->getTestHandle('foobarbaz');
        $decoder = $this->getMockForAbstractClass(AbstractDecoder::class, [$handle]);
        $this->assertNull($decoder->getLength());
        $decoder->setLength(1);
        $this->assertEquals(1, $decoder->getLength());
    }
}
