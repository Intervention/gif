<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\FrameBlock;

class FrameBlockEncoder extends AbstractEncoder
{
    /**
     * Create new decoder instance
     *
     * @param FrameBlock $source
     */
    public function __construct(FrameBlock $source)
    {
        $this->source = $source;
    }

    public function encode(): string
    {
        $graphicControlExtension = $this->source->getGraphicControlExtension();
        $colorTable = $this->source->getColorTable();
        $plainTextExtension = $this->source->getPlainTextExtension();

        return implode('', [
            implode('', array_map(function ($extension) {
                return $extension->encode();
            }, $this->source->getApplicationExtensions())),
            implode('', array_map(function ($extension) {
                return $extension->encode();
            }, $this->source->getCommentExtensions())),
            $graphicControlExtension ? $graphicControlExtension->encode() : '',
            $this->source->getImageDescriptor()->encode(),
            $colorTable ? $colorTable->encode() : '',
            $this->source->getImageData()->encode(),
            $plainTextExtension ? $plainTextExtension->encode() : '',
        ]);
    }
}
