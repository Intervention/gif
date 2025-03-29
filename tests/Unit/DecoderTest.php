<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Generator;
use Intervention\Gif\Decoder;
use Intervention\Gif\Exceptions\DecoderException;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class DecoderTest extends BaseTestCase
{
    public function testDecodeFromPath(): void
    {
        $decoded = Decoder::decode($this->getTestImagePath('animation1.gif'));
        $this->assertInstanceOf(GifDataStream::class, $decoded);
    }

    public function testDecodeFromData(): void
    {
        $decoded = Decoder::decode(file_get_contents($this->getTestImagePath('animation1.gif')));
        $this->assertInstanceOf(GifDataStream::class, $decoded);
    }

    public function testDecodeFromFilePointer(): void
    {
        $pointer = fopen('php://temp', 'r+');
        fwrite($pointer, file_get_contents($this->getTestImagePath('animation1.gif')));
        $decoded = Decoder::decode($pointer);
        $this->assertInstanceOf(GifDataStream::class, $decoded);
    }

    #[DataProvider('corruptedFilePathDataProvider')]
    public function testDecodeCorrupted(string $path): void
    {
        $this->expectException(DecoderException::class);
        Decoder::decode($path);
    }

    public static function corruptedFilePathDataProvider(): Generator
    {
        yield [self::getTestImagePath('corrupted/no_trailer.gif')];
        yield [self::getTestImagePath('corrupted/missing_global_color_table.gif')];
        yield [self::getTestImagePath('corrupted/truncated1.gif')];
        yield [self::getTestImagePath('corrupted/truncated2.gif')];
    }
}
