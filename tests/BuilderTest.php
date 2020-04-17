<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Builder;

class BuilderTest extends BaseTestCase
{
    public function testCanvas()
    {
        $img = Builder::canvas(16, 16, 1);
        $img->addFrame($this->getResource(16, 16, 255, 0, 0), 100);
        $img->addFrame($this->getResource(16, 16, 0, 255, 0), 100);
        $img->addFrame($this->getResource(16, 16, 0, 0, 255), 100);

        file_put_contents(__DIR__.'/images/builder.gif', $img->encode());
        $this->assertTrue(true);
    }

    private function getResource($width, $height, $r, $g, $b)
    {
        $resource = imagecreatetruecolor($width, $height);
        imagecolorallocate($resource, $r, $g, $b);

        return $resource;
    }
}
