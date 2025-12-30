<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Providers;

use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Blocks\CommentExtension;
use Intervention\Gif\Blocks\DataSubBlock;
use Intervention\Gif\Blocks\FrameBlock;
use Intervention\Gif\Blocks\GraphicControlExtension;
use Intervention\Gif\Blocks\Header;
use Intervention\Gif\Blocks\ImageData;
use Intervention\Gif\Blocks\ImageDescriptor;
use Intervention\Gif\Blocks\LogicalScreenDescriptor;
use Intervention\Gif\Blocks\NetscapeApplicationExtension;
use Intervention\Gif\DisposalMethod;

class BlockTypeProvider
{
    public static function header(): Header
    {
        return new Header();
    }

    public static function colorTable(): ColorTable
    {
        $table = new ColorTable();
        $table->addRgb(0, 0, 0);
        $table->addRgb(255, 0, 0);
        $table->addRgb(0, 255, 0);
        $table->addRgb(0, 0, 255);

        return $table;
    }

    public static function imageData(): ImageData
    {
        $data = new ImageData();
        $data->setLzwMinCodeSize(5);
        $data->addBlock(new DataSubBlock("\x20\x20\x8E\x64\x69\x9E\x51\xA0\x46\x67\xEB\xBE\x70\x2C\x97" .
            "\xE9\x3A\xDF\x78\xAE\xDF\x4F\xD4\x40\x8F\x9B\x43\x15\x70\xF0\x7C\xC0\x9D\xB2\x15\x02"));
        $data->addBlock(new DataSubBlock("\x01\x01\x01\x01"));
        $data->addBlock(new DataSubBlock("\x01\x01\x01"));

        return $data;
    }

    public static function imageDescriptor(
        int $size_x = 10,
        int $size_y = 10,
        int $pos_x = 0,
        int $pos_y = 0
    ): ImageDescriptor {
        $descriptor = new ImageDescriptor();
        $descriptor->setSize($size_x, $size_y);
        $descriptor->setPosition($pos_x, $pos_y);

        return $descriptor;
    }

    public static function graphicControlExtension(
        int $delay = 120,
        DisposalMethod $disposalMethod = DisposalMethod::PREVIOUS
    ): GraphicControlExtension {
        $extension = new GraphicControlExtension();
        $extension->setDelay($delay);
        $extension->setDisposalMethod($disposalMethod);
        $extension->setTransparentColorExistance();
        $extension->setTransparentColorIndex(1);
        $extension->setUserInput();

        return $extension;
    }

    public static function netscapeApplicationExtension(int $loops = 12): NetscapeApplicationExtension
    {
        $extension = new NetscapeApplicationExtension();
        $extension->setLoops($loops);

        return $extension;
    }

    public static function commentExtension(): CommentExtension
    {
        $extension = new CommentExtension();
        $extension->addComment('foo');
        $extension->addComment('bar');
        $extension->addComment('baz');

        return $extension;
    }

    public static function logicalScreenDescriptor(
        int $width = 100,
        int $height = 20
    ): LogicalScreenDescriptor {
        $descriptor = new LogicalScreenDescriptor();
        $descriptor->setSize($width, $height);

        return $descriptor;
    }

    public static function frame(): FrameBlock
    {
        $block = new FrameBlock();
        $block->setGraphicControlExtension(static::graphicControlExtension());
        $block->setImageDescriptor(static::imageDescriptor());
        $block->setImageData(static::imageData());
        $block->addApplicationExtension(static::netscapeApplicationExtension());
        $block->addCommentExtension(static::commentExtension());

        return $block;
    }
}
