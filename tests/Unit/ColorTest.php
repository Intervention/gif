<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\Color;
use Intervention\Gif\Tests\BaseTestCase;

final class ColorTest extends BaseTestCase
{
    public function testSetGetColorValues(): void
    {
        $color = new Color(1, 2, 3);
        $this->assertEquals(1, $color->red());
        $this->assertEquals(2, $color->green());
        $this->assertEquals(3, $color->blue());

        $color->setRed(4);
        $color->setGreen(5);
        $color->setBlue(6);
        $this->assertEquals(4, $color->red());
        $this->assertEquals(5, $color->green());
        $this->assertEquals(6, $color->blue());
    }

    public function testGetHash(): void
    {
        $color = new Color(1, 2, 3);
        $this->assertEquals('202cb962ac59075b964b07152d234b70', $color->hash());
    }

    public function testEncode(): void
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

    public function testDecode(): void
    {
        $source = "\x00\x00\x00";
        $color = Color::decode($this->filePointer($source));
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(0, $color->red());
        $this->assertEquals(0, $color->green());
        $this->assertEquals(0, $color->blue());

        $source = "\xff\x00\xff";
        $color = Color::decode($this->filePointer($source));
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(255, $color->red());
        $this->assertEquals(0, $color->green());
        $this->assertEquals(255, $color->blue());

        $source = "\x7d\x7d\x7d";
        $color = Color::decode($this->filePointer($source));
        $this->assertInstanceOf(Color::class, $color);
        $this->assertequals(125, $color->red());
        $this->assertequals(125, $color->green());
        $this->assertEquals(125, $color->blue());

        $source = "\x0f\x2b\x79";
        $color = Color::decode($this->filePointer($source));
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(15, $color->red());
        $this->assertEquals(43, $color->green());
        $this->assertEquals(121, $color->blue());
    }
}
