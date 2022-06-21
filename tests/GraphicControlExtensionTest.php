<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ColorTable;
use Intervention\Gif\DisposalMethod;
use Intervention\Gif\GraphicControlExtension;

class GraphicControlExtensionTest extends BaseTestCase
{
    public function testSetGetDelay()
    {
        $ext = new GraphicControlExtension();
        $this->assertEquals(0, $ext->getDelay());
        $ext->setDelay(100);
        $this->assertEquals(100, $ext->getDelay());
    }

    public function testSetGetDisposalMethod()
    {
        $ext = new GraphicControlExtension();
        $this->assertEquals(DisposalMethod::UNDEFINED, $ext->getDisposalMethod());
        $ext->setDisposalMethod(DisposalMethod::BACKGROUND);
        $this->assertEquals(DisposalMethod::BACKGROUND, $ext->getDisposalMethod());
    }

    public function testSetGetTransparentColorIndex()
    {
        $ext = new GraphicControlExtension();
        $this->assertEquals(0, $ext->getTransparentColorIndex());
        $ext->setTransparentColorIndex(100);
        $this->assertEquals(100, $ext->getTransparentColorIndex());
    }

    public function testSetGetTransparentColorExistance()
    {
        $ext = new GraphicControlExtension();
        $this->assertFalse($ext->getTransparentColorExistance());
        
        $ext->setTransparentColorExistance();
        $this->assertTrue($ext->getTransparentColorExistance());

        $ext->setTransparentColorExistance(false);
        $this->assertFalse($ext->getTransparentColorExistance());
    }

    public function testSetGetUserInput()
    {
        $ext = new GraphicControlExtension();
        $this->assertFalse($ext->getUserInput());
        
        $ext->setUserInput();
        $this->assertTrue($ext->getUserInput());

        $ext->setUserInput(false);
        $this->assertFalse($ext->getUserInput());
    }

    public function testEncode()
    {
        $extension = new GraphicControlExtension();
        $extension->setDelay(150);
        $extension->setDisposalMethod(3);
        $extension->setTransparentColorExistance();
        $extension->setTransparentColorIndex(144);
        $extension->setUserInput();
        
        $this->assertEquals("\x21\xF9\x04\x0f\x96\x00\x90\x00", $extension->encode());
    }

    public function testDecode()
    {
        $sources = [
            "\x21\xF9\x04\x0f\x96\x00\x90\x00",
        ];

        foreach ($sources as $source) {
            $extension = GraphicControlExtension::decode($this->getTestHandle($source));
            $this->assertInstanceOf(GraphicControlExtension::class, $extension);
            $this->assertEquals(150, $extension->getDelay());
            $this->assertEquals(3, $extension->getDisposalMethod());
            $this->assertTrue($extension->getTransparentColorExistance());
            $this->assertEquals(144, $extension->getTransparentColorIndex());
            $this->assertTrue($extension->getUserInput());
        }
    }
}
