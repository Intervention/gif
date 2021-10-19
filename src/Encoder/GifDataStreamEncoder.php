<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\GifDataStream;
use Intervention\Gif\GraphicBlock;

class GifDataStreamEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param GifDataStream $source
     */
    public function __construct(GifDataStream $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     *
     * @return string
     */
    public function encode(): string
    {
        return implode('', [
            $this->source->getHeader()->encode(),
            $this->source->getLogicalScreen()->encode(),
            $this->encodeData(),
            $this->source->getTrailer()->encode(),
        ]);
    }

    /**
     * Encode data blocks of source
     *
     * @return string
     */
    protected function encodeData(): string
    {
        // ...
        $application_extension_blocks = array_filter($this->source->getData(), function ($block) {
            return is_a($block, ApplicationExtension::class);
        });

        $application_extension_blocks = implode('', array_map(function ($block) {
            return $block->encode();
        }, $application_extension_blocks));

        // ...
        $graphic_blocks = array_filter($this->source->getData(), function ($block) {
            return is_a($block, GraphicBlock::class);
        });

        $graphic_blocks = implode(AbstractEntity::TERMINATOR, array_map(function ($block) {
            return $block->encode();
        }, $graphic_blocks));

        return $application_extension_blocks . $graphic_blocks;
    }
}
