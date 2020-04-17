<?php

namespace Intervention\Gif;

class Decoder
{
    /**
     * Decode given input
     *
     * @param  mixed $input
     * @return GifDataStream
     */
    public static function decode($input): GifDataStream
    {
        switch (true) {
            case self::isFilePath($input):
                $handle = self::getHandleFromFilePath($input);
                break;
            
            default:
                $handle = self::getHandleFromData($input);
                break;
        }

        return GifDataStream::decode($handle);
    }

    private static function getHandleFromData($data)
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $data);
        rewind($handle);

        return $handle;
    }

    private static function getHandleFromFilePath($path)
    {
        return fopen($path, 'rb');
    }

    /**
     * Determines if input is file path
     *
     * @return boolean
     */
    private static function isFilePath($input): bool
    {
        return is_string($input) && @is_file($input);
    }
}
