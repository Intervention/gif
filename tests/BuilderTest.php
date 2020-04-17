<?php

namespace Intervention\Gif\Test;

use Intervention\Gif\Builder;

class BuilderTest extends BaseTestCase
{
    public function testCanvas()
    {
        $img = Builder::canvas(120, 120, 2);
        $img->addFrame($this->getResource(120, 120, 255, 0, 0), 0.1, 0, 0);
        $img->addFrame($this->getResource(120, 120, 0, 255, 0), 0.1, 0, 0);
        $img->addFrame($this->getResource(120, 120, 0, 0, 255), 0.1, 0, 0);

        file_put_contents(__DIR__.'/images/builder.gif', $img->encode());
        $this->assertTrue(true);
    }

    private function getResource($width, $height, $r, $g, $b)
    {
        $resource = imagecreate($width, $height);
        imagecolorallocate($resource, $r, $g, $b);

        return $resource;
    }
}
