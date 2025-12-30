<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\DisposalMethod;
use Intervention\Gif\Blocks\GraphicControlExtension;
use Intervention\Gif\Tests\BaseTestCase;

final class GraphicControlExtensionTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $ext = new GraphicControlExtension(12, DisposalMethod::BACKGROUND);
        $this->assertEquals(12, $ext->delay());
        $this->assertEquals(DisposalMethod::BACKGROUND, $ext->disposalMethod());
    }

    public function testSetGetDelay(): void
    {
        $ext = new GraphicControlExtension();
        $this->assertEquals(0, $ext->delay());
        $ext->setDelay(100);
        $this->assertEquals(100, $ext->delay());
    }

    public function testSetGetDisposalMethod(): void
    {
        $ext = new GraphicControlExtension();
        $this->assertEquals(DisposalMethod::UNDEFINED, $ext->disposalMethod());
        $ext->setDisposalMethod(DisposalMethod::BACKGROUND);
        $this->assertEquals(DisposalMethod::BACKGROUND, $ext->disposalMethod());
    }

    public function testSetGetTransparentColorIndex(): void
    {
        $ext = new GraphicControlExtension();
        $this->assertEquals(0, $ext->transparentColorIndex());
        $ext->setTransparentColorIndex(100);
        $this->assertEquals(100, $ext->transparentColorIndex());
    }

    public function testSetGetTransparentColorExistance(): void
    {
        $ext = new GraphicControlExtension();
        $this->assertFalse($ext->transparentColorExistance());

        $ext->setTransparentColorExistance();
        $this->assertTrue($ext->transparentColorExistance());

        $ext->setTransparentColorExistance(false);
        $this->assertFalse($ext->transparentColorExistance());
    }

    public function testSetGetUserInput(): void
    {
        $ext = new GraphicControlExtension();
        $this->assertFalse($ext->userInput());

        $ext->setUserInput();
        $this->assertTrue($ext->userInput());

        $ext->setUserInput(false);
        $this->assertFalse($ext->userInput());
    }

    public function testEncode(): void
    {
        $extension = new GraphicControlExtension();
        $extension->setDelay(150);
        $extension->setDisposalMethod(DisposalMethod::PREVIOUS);
        $extension->setTransparentColorExistance();
        $extension->setTransparentColorIndex(144);
        $extension->setUserInput();

        $this->assertEquals("\x21\xF9\x04\x0f\x96\x00\x90\x00", $extension->encode());
    }

    public function testDecode(): void
    {
        $sources = [
            "\x21\xF9\x04\x0f\x96\x00\x90\x00",
        ];

        foreach ($sources as $source) {
            $extension = GraphicControlExtension::decode($this->filePointer($source));
            $this->assertInstanceOf(GraphicControlExtension::class, $extension);
            $this->assertEquals(150, $extension->delay());
            $this->assertEquals(DisposalMethod::PREVIOUS, $extension->disposalMethod());
            $this->assertTrue($extension->transparentColorExistance());
            $this->assertEquals(144, $extension->transparentColorIndex());
            $this->assertTrue($extension->userInput());
        }
    }
}
