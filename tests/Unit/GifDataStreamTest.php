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
        $this->assertInstanceOf(Header::class, $gif->getHeader());
    }

    public function testSetGetLogicalScreenDescriptor(): void
    {
        $gif = new GifDataStream();
        $gif->setLogicalScreenDescriptor(new LogicalScreenDescriptor());
        $this->assertInstanceOf(LogicalScreenDescriptor::class, $gif->getLogicalScreenDescriptor());
    }

    public function testEncode(): void
    {
        $gif = new GifDataStream();
        $gif->setLogicalScreenDescriptor($this->getTestLogicalScreenDescriptor());
        $gif->addFrame($this->getTestFrame());
        $gif->addComment($this->getTestCommentExtension());

        $result = implode('', [
            (string) $this->getTestHeader(),
            (string) $this->getTestLogicalScreenDescriptor(),
            (string) $this->getTestNetscapeApplicationExtension(),
            (string) $this->getTestCommentExtension(),
            (string) $this->getTestGraphicControlExtension(),
            (string) $this->getTestImageDescriptor(),
            (string) $this->getTestImageData(),
            (string) $this->getTestCommentExtension(),
            Trailer::MARKER,
        ]);

        $this->assertEquals($result, $gif->encode());
    }

    public function testDecode(): void
    {
        $gif = GifDataStream::decode(
            $this->getTestHandle(
                file_get_contents($this->getTestImagePath('animation1.gif'))
            ),
        );

        $this->assertInstanceOf(GifDataStream::class, $gif);

        // HEADER
        $this->assertEquals('89a', $gif->getHeader()->getVersion());

        // LOGICAL SCREEN DESCRIPTOR
        $this->assertEquals(20, $gif->getLogicalScreenDescriptor()->getWidth());
        $this->assertEquals(15, $gif->getLogicalScreenDescriptor()->getHeight());
        $this->assertTrue($gif->getLogicalScreenDescriptor()->hasGlobalColorTable());
        $this->assertFalse($gif->getLogicalScreenDescriptor()->getGlobalColorTableSorted());
        $this->assertEquals(4, $gif->getLogicalScreenDescriptor()->getGlobalColorTableSize());
        $this->assertEquals(7, $gif->getLogicalScreenDescriptor()->getBackgroundColorIndex());
        $this->assertEquals(0, $gif->getLogicalScreenDescriptor()->getPixelAspectRatio());
        $this->assertEquals(8, $gif->getLogicalScreenDescriptor()->getBitsPerPixel());

        // GLOBAL COLOR TABLE
        $this->assertInstanceOf(ColorTable::class, $gif->getGlobalColorTable());
        $this->assertEquals(32, $gif->getGlobalColorTable()->countColors());

        // NETSCAPE APPLICATION EXTENSION
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $gif->getMainApplicationExtension());
        $this->assertEquals(2, $gif->getMainApplicationExtension()->getLoops());

        // frame blocks
        $this->assertCount(8, $gif->getFrames());

        // local color tables are empty for all frames
        $colortables = array_values(array_map(function ($frame) {
            return $frame->getColorTable();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 8, null), $colortables);

        // delay for every frame
        $delays = array_values(array_map(function ($frame) {
            return $frame->getGraphicControlExtension()->getDelay();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 8, 20), $delays);

        // user input flag in every frame
        $userInputs = array_values(array_map(function ($frame) {
            return $frame->getGraphicControlExtension()->getUserInput();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 8, false), $userInputs);

        // disposal flag in every frame
        $disposals = array_values(array_map(function ($frame) {
            return $frame->getGraphicControlExtension()->getDisposalMethod();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 8, DisposalMethod::NONE), $disposals);

        // transparent color index in every frame
        $transparentIndexes = array_values(array_map(function ($frame) {
            return $frame->getGraphicControlExtension()->getTransparentColorIndex();
        }, $gif->getFrames()));
        $this->assertEquals([255, 0, 0, 0, 1, 1, 1, 1], $transparentIndexes);

        // left position in every frame
        $lefts = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getLeft();
        }, $gif->getFrames()));
        $this->assertEquals([0, 5, 1, 0, 8, 5, 1, 0], $lefts);

        $tops = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getTop();
        }, $gif->getFrames()));
        $this->assertEquals([0, 2, 0, 0, 5, 2, 0, 0], $tops);

        $widths = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getWidth();
        }, $gif->getFrames()));
        $this->assertEquals([20, 10, 17, 20, 5, 10, 17, 20], $widths);

        $heights = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getHeight();
        }, $gif->getFrames()));
        $this->assertEquals([15, 10, 15, 15, 5, 10, 15, 15], $heights);

        $localcolortables = array_values(array_map(function ($frame) {
            return $frame->hasColorTable();
        }, $gif->getFrames()));
        $this->assertEquals(array_fill(0, 8, false), $localcolortables);

        $interlaces = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->isInterlaced();
        }, $gif->getFrames()));
        $this->assertEquals([true, false, false, false, false, false, false, false], $interlaces);

        $sorts = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getLocalColorTableSorted();
        }, $gif->getFrames()));
        $this->assertEquals([false, false, false, false, false, false, false, false], $sorts);

        $sizes = array_values(array_map(function ($frame) {
            return $frame->getImageDescriptor()->getLocalColorTableSize();
        }, $gif->getFrames()));
        $this->assertEquals([0, 0, 0, 0, 0, 0, 0, 0], $sizes);
    }

    public function testDecodeTrailingComment(): void
    {
        $gif = GifDataStream::decode(
            $this->getTestHandle(
                file_get_contents($this->getTestImagePath('animation_trailing_comment.gif'))
            ),
        );

        $this->assertInstanceOf(GifDataStream::class, $gif);
        $this->assertCount(1, $gif->getComments());
    }
}
