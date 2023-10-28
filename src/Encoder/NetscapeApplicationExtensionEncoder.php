<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\NetscapeApplicationExtension;

class NetscapeApplicationExtensionEncoder extends ApplicationExtensionEncoder
{
    /**
     * Create new decoder instance
     *
     * @param NetscapeApplicationExtension $source
     */
    public function __construct(NetscapeApplicationExtension $source)
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
            ApplicationExtension::MARKER,
            ApplicationExtension::LABEL,
            pack('C', $this->source->getBlockSize()),
            $this->source->getApplication(),
            implode('', array_map(function ($block) {
                return $block->encode();
            }, $this->source->getBlocks())),
            ApplicationExtension::TERMINATOR,
        ]);
    }
}
