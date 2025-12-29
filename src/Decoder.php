<?php

declare(strict_types=1);

namespace Intervention\Gif;

use Intervention\Gif\Exceptions\FilePointerException;
use Intervention\Gif\Exceptions\InvalidArgumentException;
use Intervention\Gif\Traits\CanHandleFiles;

class Decoder
{
    use CanHandleFiles;

    /**
     * Decode given input
     */
    public static function decode(mixed $input): GifDataStream
    {
        $handle = match (true) {
            self::isFilePath($input) => self::getHandleFromFilePath($input),
            is_string($input) => self::getHandleFromData($input),
            self::isFileHandle($input) => $input,
            default => throw new InvalidArgumentException(
                'Decoder input must be either file path, file pointer resource or binary data'
            )
        };

        $result = rewind($handle);

        if ($result === false) {
            throw new FilePointerException('Failed to rewind file pointer');
        }

        return GifDataStream::decode($handle);
    }

    /**
     * Determine if input is file pointer resource
     */
    private static function isFileHandle(mixed $input): bool
    {
        return is_resource($input) && get_resource_type($input) === 'stream';
    }
}
