<?php

declare(strict_types=1);

namespace Intervention\Gif\Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    public static function imagePath(string $filename = 'animation1.gif'): string
    {
        return sprintf('%s/images/%s', __DIR__, $filename);
    }

    public function stream(string $data): mixed
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $data);
        rewind($stream);

        return $stream;
    }
}
