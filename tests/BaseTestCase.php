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

    public function filePointer(string $data): mixed
    {
        $filePointer = fopen('php://memory', 'r+');
        fwrite($filePointer, $data);
        rewind($filePointer);

        return $filePointer;
    }
}
