<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Color;
use Intervention\Gif\ColorTable;
use Intervention\Gif\Exception\DecodingException;
use Intervention\Gif\Header;
use Intervention\Gif\PlainTextExtension;
use PHPUnit\Framework\TestCase;

class PlainTextExtensionTest extends TestCase
{
    public function testSetGetData()
    {
        $extension = new PlainTextExtension;
        $this->assertEquals('', $extension->getData());

        $extension->setData('foo');
        $this->assertEquals('foo', $extension->getData());
    }

    public function testEncode()
    {
        $extension = new PlainTextExtension;
        $this->assertEquals('', $extension->encode());

        $extension->setData('foo');
        $this->assertEquals("\x21\x01\x66\x6f\x6f\x00", $extension->encode());
    }

    public function testDecode()
    {
        $source = "\x21\x01\x66\x6f\x6f\x00";
        $extension = (new PlainTextExtension)->decode($source);
        $this->assertInstanceOf(PlainTextExtension::class, $extension);
        $this->assertEquals('foo', $extension->getData());
    }
}
