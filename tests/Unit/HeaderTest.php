<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\Blocks\Header;
use Intervention\Gif\Tests\BaseTestCase;

final class HeaderTest extends BaseTestCase
{
    public function testSetGetVersion(): void
    {
        $header = new Header();
        $this->assertEquals('89a', $header->version());

        $header->setVersion('foo');
        $this->assertEquals('foo', $header->version());
    }

    public function testEncode(): void
    {
        $header = new Header();
        $this->assertEquals('GIF89a', $header->encode());
    }

    public function testDecode(): void
    {
        $header = Header::decode($this->testHandle('GIF87a'));
        $this->assertInstanceOf(Header::class, $header);
        $this->assertEquals('87a', $header->version());
    }
}
