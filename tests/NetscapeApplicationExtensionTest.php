<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\NetscapeApplicationExtension;

class NetscapeApplicationExtensionTest extends BaseTestCase
{
    public function testEncode()
    {
        // loops = 0
        $extension = new NetscapeApplicationExtension();
        $result = $extension->encode();
        $this->assertEquals("\x21\xFF\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x00\x00\x00", $result);

        // loops = 1
        $extension = new NetscapeApplicationExtension();
        $extension->setLoops(1);
        $result = $extension->encode();
        $this->assertEquals("\x21\xFF\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x01\x00\x00", $result);

        // loops = 26
        $extension = new NetscapeApplicationExtension();
        $extension->setLoops(26);
        $result = $extension->encode();
        $this->assertEquals("\x21\xFF\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x1A\x00\x00", $result);

        // loops = 11034
        $extension = new NetscapeApplicationExtension();
        $extension->setLoops(11034);
        $result = $extension->encode();
        $this->assertEquals("\x21\xFF\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x1A\x2B\x00", $result);
    }

    public function testDecode()
    {
        // loops = 0
        $source = "\x21\xFF\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x00\x00\x00";
        $extension = NetscapeApplicationExtension::decode($this->getTestHandle($source));
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $extension);
        $this->assertEquals(0, $extension->getLoops());

        // loops = 1
        $source = "\x21\xFF\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x01\x00\x00";
        $extension = NetscapeApplicationExtension::decode($this->getTestHandle($source));
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $extension);
        $this->assertEquals(1, $extension->getLoops());

        // loops = 26
        $source = "\x21\xFF\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x1A\x00\x00";
        $extension = NetscapeApplicationExtension::decode($this->getTestHandle($source));
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $extension);
        $this->assertEquals(26, $extension->getLoops());

        // loops = 111034
        $source = "\x21\xFF\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x1A\x2B\x00";
        $extension = NetscapeApplicationExtension::decode($this->getTestHandle($source));
        $this->assertInstanceOf(NetscapeApplicationExtension::class, $extension);
        $this->assertEquals(11034, $extension->getLoops());
    }
}
