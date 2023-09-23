<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Color;

class ColorTest extends BaseTestCase
{
    public function testSetGetColorValues()
    {
        $color = new Color(1, 2, 3);
        $this->assertEquals(1, $color->getRed());
        $this->assertEquals(2, $color->getGreen());
        $this->assertEquals(3, $color->getBlue());

        $color->setRed(4);
        $color->setGreen(5);
        $color->setBlue(6);
        $this->assertEquals(4, $color->getRed());
        $this->assertEquals(5, $color->getGreen());
        $this->assertEquals(6, $color->getBlue());
    }

    public function testGetHash()
    {
        $color = new Color(1, 2, 3);
        $this->assertEquals('202cb962ac59075b964b07152d234b70', $color->getHash());
    }

    public function testEncode()
    {
        $result = (new Color())->encode();
        $this->assertEquals("\x00\x00\x00", $result);

        $result = (new Color(255, 0, 255))->encode();
        $this->assertEquals("\xff\x00\xff", $result);

        $result = (new Color(125, 125, 125))->encode();
        $this->assertEquals("\x7d\x7d\x7d", $result);

        $result = (new Color(15, 43, 121))->encode();
        $this->assertEquals("\x0f\x2b\x79", $result);
    }

    public function testDecode()
    {
        $source = "\x00\x00\x00";
        $color = Color::decode($this->getTestHandle($source));
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(0, $color->getRed());
        $this->assertEquals(0, $color->getGreen());
        $this->assertEquals(0, $color->getBlue());

        $source = "\xff\x00\xff";
        $color = Color::decode($this->getTestHandle($source));
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(255, $color->getRed());
        $this->assertEquals(0, $color->getGreen());
        $this->assertEquals(255, $color->getBlue());

        $source = "\x7d\x7d\x7d";
        $color = Color::decode($this->getTestHandle($source));
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(125, $color->getRed());
        $this->assertEquals(125, $color->getGreen());
        $this->assertEquals(125, $color->getBlue());

        $source = "\x0f\x2b\x79";
        $color = Color::decode($this->getTestHandle($source));
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(15, $color->getRed());
        $this->assertEquals(43, $color->getGreen());
        $this->assertEquals(121, $color->getBlue());
    }
}
