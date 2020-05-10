<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Color;
use Intervention\Gif\ColorTable;
use Intervention\Gif\Exception\DecodingException;
use Intervention\Gif\Header;

class HeaderTest extends BaseTestCase
{
    public function testSetGetVersion()
    {
        $header = new Header();
        $this->assertEquals('87a', $header->getVersion());

        $header->setVersion('foo');
        $this->assertEquals('foo', $header->getVersion());
    }

    public function testEncode()
    {
        $header = new Header();
        $this->assertEquals('GIF87a', $header->encode());
    }

    public function testDecode()
    {
        $header = Header::decode($this->getTestHandle('GIF89a'));
        $this->assertInstanceOf(Header::class, $header);
        $this->assertEquals('89a', $header->getVersion());
    }
}
