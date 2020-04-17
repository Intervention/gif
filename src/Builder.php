<?php

namespace Intervention\Gif;

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
        $data->setGraphicControlExtension($this->buildGraphicControlExtension($delay * 100));
        $data->setGraphicRenderingBlock($this->buildTableBasedImage($resource, $left, $top));

        $this->gif->addData($data);

        return $this;
    }

    public function encode(): string
    {
        return $this->gif->encode();
    }

    protected function buildGraphicControlExtension($delay): GraphicControlExtension
    {
        $extension = new GraphicControlExtension;
        $extension->setDelay($delay);

        return $extension;
    }

    protected function buildTableBasedImage($resource, $left, $top): TableBasedImage
    {
        $block = new TableBasedImage;
        $resource = $this->decodeResource($resource);

        // add global color table from resource as local color table
        $block->getDescriptor()->setLocalColorTableExistance();
        $block->setColorTable(
            $resource->getLogicalScreen()->getColorTable()
        );
        $block->getDescriptor()->setLocalColorTableSorted(
            $resource->getLogicalScreen()->getDescriptor()->getGlobalColorTableSorted()
        );
        $block->getDescriptor()->setLocalColorTableSize(
            $resource->getLogicalScreen()->getDescriptor()->getGlobalColorTableSize()
        );
        $block->getDescriptor()->setSize(
            $resource->getLogicalScreen()->getDescriptor()->getWidth(),
            $resource->getLogicalScreen()->getDescriptor()->getHeight()
        );

        $block->getDescriptor()->setPosition($left, $top);

        // add image data from resource
        $block->setData($resource->getData()[0]->getGraphicRenderingBlock()->getData());

        return $block;
    }

    protected function decodeResource($resource): GifDataStream
    {
        ob_start();
        imagegif($resource);
        $buffer = ob_get_contents();
        ob_end_clean();

        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $buffer);
        rewind($handle);

        return GifDataStream::decode($handle);
    }
}
