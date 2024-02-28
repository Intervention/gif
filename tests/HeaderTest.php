<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests;

use Intervention\Gif\Blocks\Header;

final class HeaderTest extends BaseTestCase
{
    public function testSetGetVersion(): void
    {
        $header = new Header();
        $this->assertEquals('89a', $header->getVersion());

        $header->setVersion('foo');
        $this->assertEquals('foo', $header->getVersion());
    }

    public function testEncode(): void
    {
        $header = new Header();
        $this->assertEquals('GIF89a', $header->encode());
    }

    public function testDecode(): void
    {
        $header = Header::decode($this->getTestHandle('GIF87a'));
        $this->assertInstanceOf(Header::class, $header);
        $this->assertEquals('87a', $header->getVersion());
    }
}
