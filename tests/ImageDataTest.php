<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\DataSubBlock;
use Intervention\Gif\ImageData;

class ImageDataTest extends BaseTestCase
{
    public function testSetGetBlocks()
    {
        $data = new ImageData();
        $this->assertFalse($data->hasBlocks());
        $data->addBlock(new DataSubBlock('foo'));
        $this->assertCount(1, $data->getBlocks());
        $this->assertTrue($data->hasBlocks());
        $data->addBlock(new DataSubBlock('bar'));
        $this->assertCount(2, $data->getBlocks());
        $this->assertTrue($data->hasBlocks());
    }

    public function testEncoder()
    {
        $data = new ImageData();
        $data->setLzwMinCodeSize(5);
        $data->addBlock(new DataSubBlock("\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A\xDF\x78\xAE\xDF\x4F\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02"));
        $data->addBlock(new DataSubBlock("\x01\x01\x01\x01"));
        $data->addBlock(new DataSubBlock("\x01\x01\x01"));

        $result = "\x05\x24\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A\xDF\x78\xAE\xDF\x4F\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02\x04\x01\x01\x01\x01\x03\x01\x01\x01\x00";
        $this->assertEquals($result, $data->encode());
    }

    public function testDecode()
    {
        $source = "\x05\x24\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A\xDF\x78\xAE\xDF\x4F\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02\x04\x01\x01\x01\x01\x03\x01\x01\x01\x00";
        $data = ImageData::decode($this->getTestHandle($source));
        $this->assertInstanceOf(ImageData::class, $data);
        $this->assertEquals(5, $data->getLzwMinCodeSize());
        $this->assertCount(3, $data->getBlocks());
    }
}
