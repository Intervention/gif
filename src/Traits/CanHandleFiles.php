<?php

namespace Intervention\Gif\Traits;

use Exception;

trait CanHandleFiles
{
     /**
     * Determines if input is file path
     *
     * @return boolean
     */
    private static function isFilePath($input): bool
    {
        return is_string($input) && @is_file($input);
    }

    /**
     * Create file pointer from given gif image data
     *
     * @param  string $data
     * @return resource
     */
    private static function getHandleFromData($data)
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $data);
        rewind($handle);

        return $handle;
    }

    /**
     * Create file pounter from given file path
     *
     * @param  string $path
     * @return resource
     */
    private static function getHandleFromFilePath(string $path)
    {
        return fopen($path, 'rb');
    }
}
