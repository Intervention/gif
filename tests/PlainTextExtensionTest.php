<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Color;
use Intervention\Gif\ColorTable;
use Intervention\Gif\Exception\DecodingException;
use Intervention\Gif\Header;
use Intervention\Gif\PlainTextExtension;

class PlainTextExtensionTest extends BaseTestCase
{
    public function testSetGetText()
    {
        $extension = new PlainTextExtension();
        $this->assertCount(0, $extension->getText());

        $extension->addText('foo');
        $extension->addText('bar');
        $this->assertCount(2, $extension->getText());

        $extension->setText(['foo']);
        $this->assertCount(1, $extension->getText());
    }

    public function testEncode()
    {
        $extension = new PlainTextExtension();
        $this->assertEquals('', $extension->encode());
        $extension->addText('foo');
        $extension->addText('bar');
        $result = "\x21\x01\x0C\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x03\x66\x6f\x6f\x03\x62\x61\x72\x00";
        $this->assertEquals($result, $extension->encode());
    }

    public function testDecode()
    {
        $sources = [
            "\x21\x01\x0C\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x03\x66\x6f\x6f\x03\x62\x61\x72\x00",
        ];
        foreach ($sources as $source) {
            $extension = PlainTextExtension::decode($this->getTestHandle($source));
            $this->assertInstanceOf(PlainTextExtension::class, $extension);
            $this->assertEquals(['foo', 'bar'], $extension->getText());
        }
    }
}
