<?php

namespace Intervention\Gif;

use Intervention\Gif\Traits\CanHandleFiles;

class Decoder
{
    use CanHandleFiles;

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
}
