<?php

namespace Intervention\Gif;

use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\GraphicBlock;
use Intervention\Gif\GraphicControlExtension;

class Builder
{
    protected $gif;

    public static function canvas($width, $height, $loops = 0): self
    {
        $builder = new self;
        $gif = new GifDataStream;
        $gif->addData(new ApplicationExtension);
        $gif->getLogicalScreen()->getDescriptor()->setSize($width, $height);
        $gif->getMainApplicationExtension()->setLoops($loops);

        $builder->gif = $gif;
        
        return $builder;
    }

    public function addFrame($resource, $delay = 0, $left = 0, $top = 0): self
    {
        $data = new GraphicBlock;
        $data->setGraphicControlExtension((new GraphicControlExtension)->setDelay($delay));
        // $data->setGraphicRenderingBlock();

        $this->gif->addData($data);

        return $this;
    }

    public function encode(): string
    {
        return $this->gif->encode();
    }
}
