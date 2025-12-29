<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\Blocks\DataSubBlock;
use Intervention\Gif\Blocks\ImageData;
use Intervention\Gif\Exceptions\StateException;

class ImageDataEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     */
    public function __construct(ImageData $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     */
    public function encode(): string
    {
        if (!$this->source->hasBlocks()) {
            throw new StateException('No data blocks in image data');
        }

        return implode('', [
            pack('C', $this->source->getLzwMinCodeSize()),
            implode('', array_map(
                fn(DataSubBlock $block): string => $block->encode(),
                $this->source->getBlocks(),
            )),
            AbstractEntity::TERMINATOR,
        ]);
    }
}
