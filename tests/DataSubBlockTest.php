<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\DataSubBlock;

class DataSubBlockTest extends BaseTestCase
{
    public function testConstructor()
    {
        $block = new DataSubBlock('test');
        $this->assertInstanceOf(DataSubBlock::class, $block);
        $this->assertEquals(4, $block->getSize());
    }

    public function testGetValue(): void
    {
        $block = new DataSubBlock('test');
        $this->assertEquals('test', $block->getValue());
    }

    public function testEncode(): void
    {
        // 64 bytes block size
        $block = new DataSubBlock('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

        $result = $block->encode();
        $this->assertEquals("\x40\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78", $result);
    }

    public function testDecode(): void
    {
        $source = "\x40\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78";

        $block = DataSubBlock::decode($this->getTestHandle($source));
        $this->assertInstanceOf(DataSubBlock::class, $block);
        $this->assertEquals(64, $block->getSize());
    }
}
