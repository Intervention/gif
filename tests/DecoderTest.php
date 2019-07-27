<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Decoded;
use Intervention\Gif\Decoder;
use PHPUnit\Framework\TestCase;

class DecoderTest extends TestCase
{
    public $decoder;

    public function setUp(): void
    {
        $this->decoder = $this->getTestDecoder('tests/images/animation.gif');
    }

    private function getTestDecoder($file)
    {
        return new Decoder($file);
    }

    public function testConstructorFromFile()
    {
        $decoder = new Decoder('tests/images/animation.gif');
        $this->assertInstanceOf(Decoder::class, $decoder);
    }

    public function testInitFromData()
    {
        $data = file_get_contents('tests/images/animation.gif');

        $decoder = new Decoder;
        $decoder->initFromData($data);
        $this->assertInstanceOf(Decoder::class, $decoder);
    }

    public function testDecode()
    {
        $decoded = $this->decoder->decode();

        $this->assertInstanceOf(Decoded::class, $decoded);
        $this->assertEquals(8, $decoded->countFrames());
        $this->assertTrue($decoded->hasGlobalColorTable());
        $this->assertEquals(32, $decoded->countGlobalColors());
        $this->assertEquals(2, $decoded->getLoops());

        $offsets = [
            ['left' => 0, 'top' => 0],
            ['left' => 5, 'top' => 2],
            ['left' => 1, 'top' => 0],
            ['left' => 0, 'top' => 0],
            ['left' => 8, 'top' => 5],
            ['left' => 5, 'top' => 2],
            ['left' => 1, 'top' => 0],
            ['left' => 0, 'top' => 0]
        ];

        $sizes = [
            ['width' => 20, 'height' => 15],
            ['width' => 10, 'height' => 10],
            ['width' => 17, 'height' => 15],
            ['width' => 20, 'height' => 15],
            ['width' => 5, 'height' => 5],
            ['width' => 10, 'height' => 10],
            ['width' => 17, 'height' => 15],
            ['width' => 20, 'height' => 15]
        ];

        $delays = [20, 20, 20, 20, 20, 20, 20, 20];
        $interlaced = [true, false, false, false, false, false, false, false];
        $localColorTables = [null, null, null, null, null, null, null, null];

        foreach ($decoded->getFrames() as $key => $frame) {
            $this->assertEquals($sizes[$key]['width'], $frame->size->width);
            $this->assertEquals($sizes[$key]['height'], $frame->size->height);
            $this->assertEquals($offsets[$key]['left'], $frame->offset->left);
            $this->assertEquals($offsets[$key]['top'], $frame->offset->top);
            $this->assertEquals($delays[$key], $frame->decodeDelay());
            $this->assertEquals($interlaced[$key], $frame->isInterlaced());
            $this->assertEquals($localColorTables[$key], $frame->getLocalColorTable());
            $this->assertFalse($frame->hasLocalColorTable());
        }
    }
}
