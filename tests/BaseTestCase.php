<?php

namespace Intervention\Gif\Test;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    public function getTestHandle($data)
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $data);
        rewind($handle);

        return $handle;
    }
}
