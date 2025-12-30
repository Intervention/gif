<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\ApplicationExtension;
use Intervention\Gif\Blocks\DataSubBlock;
use Intervention\Gif\Tests\BaseTestCase;

final class ApplicationExtensionTest extends BaseTestCase
{
    public function testSetGetApplication(): void
    {
        $ext = new ApplicationExtension();
        $this->assertEquals('', $ext->getApplication());

        $ext->setApplication('foo');
        $this->assertEquals('foo', $ext->getApplication());
    }

    public function testAddBlock(): void
    {
        $extension = new ApplicationExtension();
        $this->assertCount(0, $extension->getBlocks());
        $extension->addBlock(new DataSubBlock('foo'));
        $extension->addBlock(new DataSubBlock('bar'));
        $this->assertCount(2, $extension->getBlocks());
    }

    public function testGetFirstBlock(): void
    {
        $extension = new ApplicationExtension();
        $extension->addBlock(new DataSubBlock('foo'));
        $this->assertInstanceOf(DataSubBlock::class, $extension->getFirstBlock());
    }

    public function testEncode(): void
    {
        $extension = new ApplicationExtension();
        $extension->setApplication('foobar');
        $extension->addBlock(new DataSubBlock('baz'));
        $result = $extension->encode();
        $this->assertEquals("\x21\xff\x06\x66\x6F\x6F\x62\x61\x72\x03\x62\x61\x7A\x00", $result);

        $extension = new ApplicationExtension();
        $extension->setApplication('NETSCAPE2.0');
        $extension->addBlock(new DataSubBlock("\x01\x0c\x00"));
        $result = $extension->encode();
        $this->assertEquals("\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\x0c\x00\x00", $result);
    }

    public function testDecode(): void
    {
        $source = "\x21\xff\x06\x66\x6F\x6F\x62\x61\x72\x03\x62\x61\x7A\x00";
        $extension = ApplicationExtension::decode($this->filePointer($source));
        $this->assertInstanceOf(ApplicationExtension::class, $extension);
        $this->assertCount(1, $extension->getBlocks());
        $this->assertEquals('foobar', $extension->getApplication());
    }
}
