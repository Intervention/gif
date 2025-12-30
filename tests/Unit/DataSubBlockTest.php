<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\DataSubBlock;
use Intervention\Gif\Tests\BaseTestCase;

final class DataSubBlockTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $block = new DataSubBlock('test');
        $this->assertInstanceOf(DataSubBlock::class, $block);
        $this->assertEquals(4, $block->size());
    }

    public function testGetValue(): void
    {
        $block = new DataSubBlock('test');
        $this->assertEquals('test', $block->value());
    }

    public function testEncode(): void
    {
        // 64 bytes block size
        $block = new DataSubBlock('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

        $result = $block->encode();
        $this->assertEquals("\x40\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78" .
            "\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78" .
            "\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78", $result);
    }

    public function testDecode(): void
    {
        $source = "\x40\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78" .
            "\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78" .
            "\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78\x78";

        $block = DataSubBlock::decode($this->filePointer($source));
        $this->assertInstanceOf(DataSubBlock::class, $block);
        $this->assertEquals(64, $block->size());
    }
}
