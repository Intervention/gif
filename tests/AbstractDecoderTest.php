<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Decoder\AbstractDecoder;

class AbstractDecoderTest extends BaseTestCase
{
    public function testConstructorWithCallback()
    {
        $callback = function ($decoder) {
            $decoder->setLength(12);
        };
        $handle = $this->getTestHandle('foobarbaz');
        $decoder = $this->getMockForAbstractClass(AbstractDecoder::class, [$handle, $callback]);
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
