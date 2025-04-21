<?php

declare(strict_types=1);

namespace Intervention\Gif\Encoders;

use Intervention\Gif\Blocks\ApplicationExtension;
use Intervention\Gif\Blocks\DataSubBlock;
use Intervention\Gif\Exceptions\EncoderException;

class ApplicationExtensionEncoder extends AbstractEncoder
{
    /**
     * Create new decoder instance
     *
     * @param ApplicationExtension $source
     */
    public function __construct(ApplicationExtension $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     *
     * @throws EncoderException
     * @return string
     */
    public function encode(): string
    {
        return implode('', [
            ApplicationExtension::MARKER,
            ApplicationExtension::LABEL,
            pack('C', $this->source->blockSize()),
            $this->source->application(),
            implode('', array_map(fn(DataSubBlock $block): string => $block->encode(), $this->source->blocks())),
            ApplicationExtension::TERMINATOR,
        ]);
    }
}
