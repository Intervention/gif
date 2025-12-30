<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\CommentExtension;
use Intervention\Gif\Tests\BaseTestCase;

final class CommentExtensionTest extends BaseTestCase
{
    public function testSetGetComment(): void
    {
        $extension = new CommentExtension();
        $extension->addComment('foo');
        $extension->addComment('bar');
        $extension->addComment('baz');
        $this->assertIsArray($extension->getComments());
        $this->assertCount(3, $extension->getComments());
        $this->assertEquals('foo', $extension->getComment(0));
        $this->assertEquals('bar', $extension->getComment(1));
        $this->assertEquals('baz', $extension->getComment(2));
    }

    public function testEncode(): void
    {
        $extension = new CommentExtension();
        $extension->addComment('blueberry');
        $result = "\x21\xFE\x09\x62\x6C\x75\x65\x62\x65\x72\x72\x79\x00";
        $this->assertEquals($result, $extension->encode());

        $extension = new CommentExtension();
        $extension->addComment('foo');
        $extension->addComment('bar');
        $extension->addComment('baz');
        $result = "\x21\xFE\x03\x66\x6F\x6F\x03\x62\x61\x72\x03\x62\x61\x7a\x00";
        $this->assertEquals($result, $extension->encode());
    }

    public function testDecode(): void
    {
        $sources = [
            "\x21\xFE\x09\x62\x6C\x75\x65\x62\x65\x72\x72\x79\x00",
        ];

        foreach ($sources as $source) {
            $extension = CommentExtension::decode($this->filePointer($source));
            $this->assertInstanceOf(CommentExtension::class, $extension);
            $this->assertCount(1, $extension->getComments());
            $this->assertEquals('blueberry', $extension->getComment(0));
        }

        $sources = [
            "\x21\xFE\x03\x66\x6F\x6F\x03\x62\x61\x72\x03\x62\x61\x7a\x00",
        ];

        foreach ($sources as $source) {
            $extension = CommentExtension::decode($this->filePointer($source));
            $this->assertInstanceOf(CommentExtension::class, $extension);
            $this->assertCount(3, $extension->getComments());
            $this->assertEquals('foo', $extension->getComment(0));
            $this->assertEquals('bar', $extension->getComment(1));
            $this->assertEquals('baz', $extension->getComment(2));
        }
    }
}
