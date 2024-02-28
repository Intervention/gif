<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests\Unit;

use Intervention\Gif\DisposalMethod;
use PHPUnit\Framework\TestCase;

final class DisposalMethodTest extends TestCase
{
    public function testIntegerValues(): void
    {
        $this->assertEquals(0, DisposalMethod::UNDEFINED->value);
        $this->assertEquals(1, DisposalMethod::NONE->value);
        $this->assertEquals(2, DisposalMethod::BACKGROUND->value);
        $this->assertEquals(3, DisposalMethod::PREVIOUS->value);
    }
}
