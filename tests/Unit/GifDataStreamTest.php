<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\ColorTable;
use Intervention\Gif\Blocks\Header;
use Intervention\Gif\Blocks\LogicalScreenDescriptor;
use Intervention\Gif\Blocks\NetscapeApplicationExtension;
use Intervention\Gif\Blocks\Trailer;
use Intervention\Gif\DisposalMethod;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\Tests\BaseTestCase;

final class GifDataStreamTest extends BaseTestCase
{
    public function testSetGetHeader(): void
    {
        $gif = new GifDataStream();
        $gif->setHeader(new Header());
        $this->assertInstanceOf(Header::class, $gif->header());
    }

    public function testSetGetLogicalScreenDescriptor(): void
    {
        $gif = new GifDataStream();
        $gif->setLogicalScreenDescriptor(new LogicalScreenDescriptor());
        $this->assertInstanceOf(LogicalScreenDescriptor::class, $gif->logicalScreenDescriptor());
    }

    public function testEncode(): void
    {
        $gif = new GifDataStream();
        $gif->setLogicalScreenDescriptor($this->testLogicalScreenDescriptor());
        $gif->addFrame($this->testFrame());
        $gif->addComment($this->testCommentExtension());

        $result = implode('', [
            (string) $this->testHeader(),
            (string) $this->testLogicalScreenDescriptor(),
            (string) $this->testNetscapeApplicationExtension(),
            (string) $this->testCommentExtension(),
            (string) $this->testGraphicControlExtension(),
            (string) $this->testImageDescriptor(),
            (string) $this->testImageData(),
            (string) $this->testCommentExtension(),
            Trailer::MARKER,
        ]);

        $this->assertEquals($result, $gif->encode());
    }

    public function testDecode(): void
    {
        $gif = GifDataStream::decode(
            $this->testHandle(
                file_get_contents($this->testImagePath('animation1.gif'))
            ),
        );

        $this->assertInstanceOf(GifDataStream::class, $gif);

        // HEADER
        $this->assertEquals('89a', $gif->header()->version());

        // LOGICAL SCREEN DESCRIPTOR
        $this->assertEquals(20, $gif->logicalScreenDescriptor()->width());
        $this->assertEquals(15, $gif->logicalScreenDescriptor()->height());
        $this->assertTrue($gif->logicalScreenDescriptor()->hasGlobalColorTable());
        $this->assertFalse($gif->logicalScreenDescriptor()->globalColorTableSorted());
        $this->assertEquals(4, $gif->logicalScreenDescriptor()->globalColorTableSize());
        $this->assertEquals(7, $gif->logicalScreenDescriptor()->backgroundColorIndex());
        $this->assertEquals(0, $gif->logicalScreenDescriptor()->pixelAspectRatio());
        $this->assertEquals(8, $gif->logicalScreenDescriptor()->bitsPerPixel());

        // GLOBAL COLOR TABLE
        $this->assertInstanceOf(ColorTable::class, $gif->globalColorTable());
        $this->assertEquals(32, $gif->globalColorTable()->countColors());

        // NETSCAPE APPLICATION EXTENSION
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $gif->mainApplicationExtension());
        $this->assertEquals(2, $gif->mainApplicationExtension()->loops());

        // frame blocks
        $this->assertCount(8, $gif->frames());

        // local color tables are empty for all frames
        $colortables = array_values(array_map(function ($frame) {
            return $frame->colorTable();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 8, null), $colortables);

        // delay for every frame
        $delays = array_values(array_map(function ($frame) {
            return $frame->graphicControlExtension()->delay();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 8, 20), $delays);

        // user input flag in every frame
        $userInputs = array_values(array_map(function ($frame) {
            return $frame->graphicControlExtension()->userInput();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 8, false), $userInputs);

        // disposal flag in every frame
        $disposals = array_values(array_map(function ($frame) {
            return $frame->graphicControlExtension()->disposalMethod();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 8, DisposalMethod::NONE), $disposals);

        // transparent color index in every frame
        $transparentIndexes = array_values(array_map(function ($frame) {
            return $frame->graphicControlExtension()->transparentColorIndex();
        }, $gif->frames()));
        $this->assertEquals([255, 0, 0, 0, 1, 1, 1, 1], $transparentIndexes);

        // left position in every frame
        $lefts = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->left();
        }, $gif->frames()));
        $this->assertEquals([0, 5, 1, 0, 8, 5, 1, 0], $lefts);

        $tops = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->top();
        }, $gif->frames()));
        $this->assertEquals([0, 2, 0, 0, 5, 2, 0, 0], $tops);

        $widths = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->width();
        }, $gif->frames()));
        $this->assertEquals([20, 10, 17, 20, 5, 10, 17, 20], $widths);

        $heights = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->height();
        }, $gif->frames()));
        $this->assertEquals([15, 10, 15, 15, 5, 10, 15, 15], $heights);

        $localcolortables = array_values(array_map(function ($frame) {
            return $frame->hasColorTable();
        }, $gif->frames()));
        $this->assertEquals(array_fill(0, 8, false), $localcolortables);

        $interlaces = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->isInterlaced();
        }, $gif->frames()));
        $this->assertEquals([true, false, false, false, false, false, false, false], $interlaces);

        $sorts = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->localColorTableSorted();
        }, $gif->frames()));
        $this->assertEquals([false, false, false, false, false, false, false, false], $sorts);

        $sizes = array_values(array_map(function ($frame) {
            return $frame->imageDescriptor()->localColorTableSize();
        }, $gif->frames()));
        $this->assertEquals([0, 0, 0, 0, 0, 0, 0, 0], $sizes);
    }

    public function testDecodeTrailingComment(): void
    {
        $gif = GifDataStream::decode(
            $this->testHandle(
                file_get_contents($this->testImagePath('animation_trailing_comment.gif'))
            ),
        );

        $this->assertInstanceOf(GifDataStream::class, $gif);
        $this->assertCount(1, $gif->comments());
    }
}
