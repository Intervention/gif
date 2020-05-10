<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\CommentExtension;

class CommentExtensionTest extends BaseTestCase
{
    public function testSetGetComment()
    {
        $extension = new CommentExtension();
        $extension->addComment('foo');
        $extension->addComment('bar');
        $extension->addComment('baz');
        $this->assertIsArray($extension->getComments());
        $this->assertCount(3, $extension->getComments());
        $this->assertEquals('foo', $extension->getComments(0));
        $this->assertEquals('bar', $extension->getComments(1));
        $this->assertEquals('baz', $extension->getComments(2));
    }

    public function testEncode()
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

    public function testDecode()
    {
        $sources = [
            "\x21\xFE\x09\x62\x6C\x75\x65\x62\x65\x72\x72\x79\x00",
        ];

        foreach ($sources as $source) {
            $extension = CommentExtension::decode($this->getTestHandle($source));
            $this->assertInstanceOf(CommentExtension::class, $extension);
            $this->assertCount(1, $extension->getComments());
            $this->assertEquals('blueberry', $extension->getComments(0));
        }

        $sources = [
            "\x21\xFE\x03\x66\x6F\x6F\x03\x62\x61\x72\x03\x62\x61\x7a\x00",
        ];

        foreach ($sources as $source) {
            $extension = CommentExtension::decode($this->getTestHandle($source));
            $this->assertInstanceOf(CommentExtension::class, $extension);
            $this->assertCount(3, $extension->getComments());
            $this->assertEquals('foo', $extension->getComments(0));
            $this->assertEquals('bar', $extension->getComments(1));
            $this->assertEquals('baz', $extension->getComments(2));
        }
    }
}
