<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ColorTable;
use Intervention\Gif\CommentExtension;
use Intervention\Gif\DataSubBlock;
use Intervention\Gif\GraphicBlock;
use Intervention\Gif\GraphicControlExtension;
use Intervention\Gif\ImageData;
use Intervention\Gif\ImageDescriptor;
use Intervention\Gif\LogicalScreen;
use Intervention\Gif\LogicalScreenDescriptor;
use Intervention\Gif\NetscapeApplicationExtension;
use Intervention\Gif\TableBasedImage;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    public const HEADER_SAMPLE = "GIF89a";
    public const LOGICAL_SCREEN_DESCRIPTOR_SAMPLE = "\x51\x00\x16\x00\xf1\x00\x00\x00\x00\x00\xff\x00\x00\x00\xff\x00\x00\x00\xff";
    public const GRAPHIC_CONTROL_EXTENSION_SAMPLE = "\x21\xF9\x04\x0f\x96\x00\x01\x00";
    public const TABLE_BASED_IMAGE_SAMPLE = "\x2c\x0a\x00\x0a\x00\x0a\x00\x0a\x00\x00\x02\x16\x8c\x2d\x99\x87\x2a\x1c\xdc\x33\xa0\x02\x75\xec\x95\xfa\xa8\xde\x60\x8c\x04\x91\x4c\x01\x00";
    public const COMMENT_EXTENSION_SAMPLE = "\x21\xFE\x03\x66\x6F\x6F\x03\x62\x61\x72\x03\x62\x61\x7a\x00";
    public const APPLICATION_EXTENSION_SAMPLE = "\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\x0c\x00\x00";

    public function getTestHandle($data)
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $data);
        rewind($handle);

        return $handle;
    }

    protected function getTestColorTable(): ColorTable
    {
        $table = new ColorTable();
        $table->addRgb(0, 0, 0);
        $table->addRgb(255, 0, 0);
        $table->addRgb(0, 255, 0);
        $table->addRgb(0, 0, 255);

        return $table;
    }

    protected function getTestImageData(): ImageData
    {
        $data = new ImageData();
        $data->setLzwMinCodeSize(2);
        $data->addBlock(
            new DataSubBlock("\x8C\x2D\x99\x87\x2A\x1C\xDC\x33\xA0\x02\x75\xEC\x95\xFA\xA8\xDE\x60\x8C\x04\x91\x4C\x01")
        );

        return $data;
    }

    protected function getTestImageDescriptor(): ImageDescriptor
    {
        $descriptor = new ImageDescriptor();
        $descriptor->setSize(10, 10);
        $descriptor->setPosition(10, 10);

        return $descriptor;
    }

    protected function getTestTableBasedImage(): TableBasedImage
    {
        $tbi = new TableBasedImage();
        $tbi->setDescriptor($this->getTestImageDescriptor());
        $tbi->setData($this->getTestImageData());

        return $tbi;
    }

    protected function getTestGraphicControlExtension(): GraphicControlExtension
    {
        $extension = new GraphicControlExtension();
        $extension->setDelay(150);
        $extension->setDisposalMethod(3);
        $extension->setTransparentColorExistance();
        $extension->setTransparentColorIndex(1);
        $extension->setUserInput();

        return $extension;
    }

    protected function getTestGraphicBlock(): GraphicBlock
    {
        $block = new GraphicBlock();
        $block->setGraphicControlExtension($this->getTestGraphicControlExtension());
        $block->setGraphicRenderingBlock($this->getTestTableBasedImage());

        return $block;
    }

    protected function getTestNetscapeApplicationExtension(): NetscapeApplicationExtension
    {
        $extension = new NetscapeApplicationExtension();
        $extension->setLoops(12);

        return $extension;
    }

    protected function getTestCommentExtension(): CommentExtension
    {
        $extension = new CommentExtension();
        $extension->addComment('foo');
        $extension->addComment('bar');
        $extension->addComment('baz');

        return $extension;
    }

    protected function getTestLogicalScreen()
    {
        $screen = new LogicalScreen();
        $screen->setDescriptor($this->getTestLogicalScreenDescriptor());
        $screen->setColorTable($this->getTestColorTable());

        return $screen;
    }

    protected function getTestLogicalScreenDescriptor()
    {
        $descriptor = new LogicalScreenDescriptor();
        $descriptor->setSize(81, 22);
        $descriptor->setGlobalColorTableExistance(true);
        $descriptor->setGlobalColorTableSorted(false);
        $descriptor->setGlobalColorTableSize(1);
        $descriptor->setBackgroundColorIndex(0);

        return $descriptor;
    }
}
