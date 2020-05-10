<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ImageData;

class ImageDataTest extends BaseTestCase
{
    public function testSetGetBlocks()
    {
        $data = new ImageData();
        $this->assertCount(0, $data->getBlocks());

        $data->addBlock('foo');
        $data->addBlock('bar');
        $this->assertCount(2, $data->getBlocks());

        $data->setBlocks(['foo']);
        $this->assertCount(1, $data->getBlocks());
    }

    public function testEncoder()
    {
        $data = new ImageData();
        $data->addBlock("\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01");
        $data->addBlock("\x09\x03\x01");

        $result = "\x02\x16\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01\x03\x09\x03\x01\x00";
        $this->assertEquals($result, $data->encode());
    }

    public function testDecode()
    {
        $source = "\x02\x16\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01\x03\x09\x03\x01\x00";
        $data = ImageData::decode($this->getTestHandle($source));
        $this->assertInstanceOf(ImageData::class, $data);
        $this->assertCount(2, $data->getBlocks());
    }
}
