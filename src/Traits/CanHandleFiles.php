<?php

declare(strict_types=1);

namespace Intervention\Gif\Traits;

trait CanHandleFiles
{
     /**
     * Determines if input is file path
     */
    private static function isFilePath(mixed $input): bool
    {
        return is_string($input) && !self::hasNullBytes($input) && @is_file($input);
    }

    /**
     * Determine if given string contains null bytes
     */
    private static function hasNullBytes(string $string): bool
    {
        return str_contains($string, chr(0));
    }

    /**
     * Create file pointer from given gif image data
     */
    private static function getHandleFromData(string $data): mixed
    {
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, $data);
        rewind($handle);

        return $handle;
    }

    /**
     * Create file pounter from given file path
     */
    private static function getHandleFromFilePath(string $path): mixed
    {
        return fopen($path, 'rb');
    }
}
