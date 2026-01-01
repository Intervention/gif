<?php

declare(strict_types=1);

namespace Intervention\Gif\Traits;

use Intervention\Gif\Exceptions\FilePointerException;

trait CanHandleFiles
{
     /**
     * Determines if input is file path.
     */
    private static function isFilePath(mixed $input): bool
    {
        return is_string($input) && !self::hasNullBytes($input) && @is_file($input) === true;
    }

    /**
     * Determine if given string contains null bytes.
     */
    private static function hasNullBytes(string $string): bool
    {
        return str_contains($string, chr(0));
    }

    /**
     * Create file pointer from given gif image data.
     *
     * @throws FilePointerException
     */
    private static function filePointerFromData(string $data): mixed
    {
        $filePointer = fopen('php://temp', 'r+');

        if ($filePointer === false) {
            throw new FilePointerException('Failed to create tempory file pointer');
        }

        $result = fwrite($filePointer, $data);
        if ($result === false) {
            throw new FilePointerException('Failed to write tempory file pointer');
        }

        $result = rewind($filePointer);
        if ($result === false) {
            throw new FilePointerException('Failed to rewind tempory file pointer');
        }

        return $filePointer;
    }

    /**
     * Create file pounter from given file path.
     *
     * @throws FilePointerException
     */
    private static function filePointerFromFilePath(string $path): mixed
    {
        $filePointer = fopen($path, 'rb');

        if ($filePointer === false) {
            throw new FilePointerException('Failed to create file pointer from path');
        }

        return $filePointer;
    }
}
