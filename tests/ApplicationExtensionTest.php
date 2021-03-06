<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\ApplicationExtension;

class ApplicationExtensionTest extends BaseTestCase
{
    public function testSetGetLoops()
    {
        $ext = new ApplicationExtension();
        $this->assertEquals(0, $ext->getLoops());
        
        $ext->setLoops(12);
        $this->assertEquals(12, $ext->getLoops());
    }

    public function testEncode()
    {
        $extension = new ApplicationExtension();
        
        $result = $extension->setLoops(1)->encode();
        $this->assertEquals("\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\x01\x00\x00", $result);

        $result = $extension->setLoops(12)->encode();
        $this->assertEquals("\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\x0c\x00\x00", $result);

        $result = $extension->setLoops(65535)->encode();
        $this->assertEquals("\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\xff\xff\x00", $result);
    }

    public function testDecode()
    {
        $sources = [
            "\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\x01\x00\x00",
        ];
        foreach ($sources as $source) {
            $extension = ApplicationExtension::decode($this->getTestHandle($source));
            $this->assertInstanceOf(ApplicationExtension::class, $extension);
            $this->assertEquals(1, $extension->getLoops());
        }

        $sources = [
            "\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\x0c\x00\x00",
        ];
        foreach ($sources as $source) {
            $extension = ApplicationExtension::decode($this->getTestHandle($source));
            $this->assertInstanceOf(ApplicationExtension::class, $extension);
            $this->assertEquals(12, $extension->getLoops());
        }

        $sources = [
            "\x21\xff\x0b\x4e\x45\x54\x53\x43\x41\x50\x45\x32\x2e\x30\x03\x01\xff\xff\x00",
        ];
        foreach ($sources as $source) {
            $extension = ApplicationExtension::decode($this->getTestHandle($source));
            $this->assertInstanceOf(ApplicationExtension::class, $extension);
            $this->assertEquals(65535, $extension->getLoops());
        }
    }
}
