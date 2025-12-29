<?php

declare(strict_types=1);

namespace Intervention\Gif\Traits;

use Intervention\Gif\Exceptions\FilePointerException;

trait CanHandleFiles
{
     /**
     * Determines if input is file path
     */
    private static function isFilePath(mixed $input): bool
    {
        return is_string($input) && !self::hasNullBytes($input) && @is_file($input) === true;
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

        if ($handle === false) {
            throw new FilePointerException('Failed to create tempory file handle');
        }

        $result = fwrite($handle, $data);
        if ($result === false) {
            throw new FilePointerException('Failed to write tempory file handle');
        }

        $result = rewind($handle);
        if ($result === false) {
            throw new FilePointerException('Failed to rewind tempory file handle');
        }

        return $handle;
    }

    /**
     * Create file pounter from given file path
     */
    private static function getHandleFromFilePath(string $path): mixed
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new FilePointerException('Failed to create file handle from path');
        }

        return $handle;
    }
}
