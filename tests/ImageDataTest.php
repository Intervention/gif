<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ImageData;

class ImageDataTest extends BaseTestCase
{
    public function testSetGetData()
    {
        $data = new ImageData();
        $data->setData('foo');
        $this->assertEquals('foo', $data->getData());
    }

    public function testEncoder()
    {
        $data = new ImageData();
        $data->setLzwMinCodeSize(2);
        $data->setData("\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01");

        $result = "\x02\x16\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01\x00";
        $this->assertEquals($result, $data->encode());
    }

    public function testDecode()
    {
        $source = "\x02\x16\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01\x00";
        $data = ImageData::decode($this->getTestHandle($source));
        $this->assertInstanceOf(ImageData::class, $data);
        $this->assertEquals(2, $data->getLzwMinCodeSize());
        $this->assertEquals("\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01", $data->getData());
    }
}
