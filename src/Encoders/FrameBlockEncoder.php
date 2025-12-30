<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

use Intervention\Gif\Blocks\ApplicationExtension;
use Intervention\Gif\Blocks\CommentExtension;
use Intervention\Gif\Blocks\FrameBlock;

class FrameBlockEncoder extends AbstractEncoder
{
    /**
     * Create new decoder instance
     */
    public function __construct(FrameBlock $source)
    {
        $this->source = $source;
    }

    public function encode(): string
    {
        $graphicControlExtension = $this->source->graphicControlExtension();
        $colorTable = $this->source->colorTable();
        $plainTextExtension = $this->source->plainTextExtension();

        return implode('', [
            implode('', array_map(
                fn(ApplicationExtension $extension): string => $extension->encode(),
                $this->source->applicationExtensions(),
            )),
            implode('', array_map(
                fn(CommentExtension $extension): string => $extension->encode(),
                $this->source->commentExtensions(),
            )),
            $plainTextExtension ? $plainTextExtension->encode() : '',
            $graphicControlExtension ? $graphicControlExtension->encode() : '',
            $this->source->imageDescriptor()->encode(),
            $colorTable ? $colorTable->encode() : '',
            $this->source->imageData()->encode(),
        ]);
    }
}
