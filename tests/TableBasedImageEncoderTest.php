<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Blocks\Color;
use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Blocks\DataSubBlock;
use Intervention\Gif\Blocks\ImageData;
use Intervention\Gif\Blocks\TableBasedImage;

class TableBasedImageEncoderTest extends BaseTestCase
{
    public function testEncode(): void
    {
        $tbi = new TableBasedImage();
        $tbi->setImageDescriptor(
            $this->getTestImageDescriptor()
                ->setSize(10, 10)
                ->setPosition(10, 10)
        );

        $tbi->setColorTable(new ColorTable([
            new Color(0, 0, 0),
            new Color(255, 0, 0),
            new Color(255, 255, 0),
            new Color(255, 255, 255),
        ]));

        $tbi->setImageData((new ImageData())
            ->setLzwMinCodeSize(5)
            ->addBlock(new DataSubBlock("\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A\xDF\x78\xAE\xDF\x4F\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02"))
            ->addBlock(new DataSubBlock("\x01\x01\x01\x01"))
            ->addBlock(new DataSubBlock("\x01\x01\x01")));

        $result = implode('', [
            "\x2C\x0A\x00\x0A\x00\x0A\x00\x0A\x00\x00",
            "\x00\x00\x00\xff\x00\x00\xff\xff\x00\xff\xff\xff",
            "\x05\x24\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97\xE9\x3A\xDF\x78\xAE\xDF\x4F\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02\x04\x01\x01\x01\x01\x03\x01\x01\x01\x00"
        ]);

        $this->assertEquals($result, $tbi->encode());
    }
}
