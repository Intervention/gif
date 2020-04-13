<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ImageData;
use PHPUnit\Framework\TestCase;

class ImageDataTest extends TestCase
{
    public function testSetGetData()
    {
        $extension = new ImageData;
        $this->assertEquals('', $extension->getData());

        $extension->setData('foo');
        $this->assertEquals('foo', $extension->getData());
    }

    public function testEncode()
    {
        $extension = new ImageData;
        $this->assertEquals('', $extension->encode());

        $extension->setData('foo');
        $this->assertEquals("foo", $extension->encode());
    }

    public function testDecode()
    {
        $source = "foo";
        $extension = (new ImageData)->decode($source);
        $this->assertInstanceOf(ImageData::class, $extension);
        $this->assertEquals('foo', $extension->getData());
    }
}
