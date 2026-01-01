<?php

declare(strict_types=1);

namespace Intervention\Gif;

use Intervention\Gif\Exceptions\DecoderException;
use Intervention\Gif\Exceptions\FilePointerException;
use Intervention\Gif\Exceptions\InvalidArgumentException;
use Intervention\Gif\Traits\CanHandleFiles;

class Decoder
{
    use CanHandleFiles;

    /**
     * Decode given input.
     *
     * @throws InvalidArgumentException
     * @throws FilePointerException
     * @throws DecoderException
     */
    public static function decode(mixed $input): GifDataStream
    {
        $filePointer = match (true) {
            self::isFilePath($input) => self::filePointerFromFilePath($input),
            is_string($input) => self::filePointerFromData($input),
            self::isFilePointer($input) => $input,
            default => throw new InvalidArgumentException(
                'Decoder input must be either file path, file pointer resource or binary data'
            )
        };

        $result = rewind($filePointer);

        if ($result === false) {
            throw new FilePointerException('Failed to rewind file pointer');
        }

        return GifDataStream::decode($filePointer);
    }

    /**
     * Determine if input is file pointer resource.
     */
    private static function isFilePointer(mixed $input): bool
    {
        return is_resource($input) && get_resource_type($input) === 'stream';
    }
}
