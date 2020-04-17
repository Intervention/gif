<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\ApplicationExtension;

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
     * @return string
     */
    public function encode(): string
    {
        return implode('', [
            ApplicationExtension::MARKER,
            ApplicationExtension::LABEL,
            ApplicationExtension::BLOCKSIZE,
            ApplicationExtension::IDENT,
            ApplicationExtension::AUTH,
            ApplicationExtension::SUB_BLOCKSIZE,
            ApplicationExtension::BLOCK_INT,
            $this->encodeLoops(),
            ApplicationExtension::TERMINATOR,
        ]);
    }

    /**
     * Encode loops value
     *
     * @return string
     */
    protected function encodeLoops(): string
    {
        return pack('v*', $this->source->getLoops());
    }
}
