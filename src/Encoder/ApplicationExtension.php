<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\ApplicationExtension as ApplicationExtensionObject;

class ApplicationExtension extends AbstractEncoder
{
    /**
     * Create new decoder instance
     *
     * @param ApplicationExtensionObject $source
     */
    public function __construct(ApplicationExtensionObject $source)
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
            ApplicationExtensionObject::MARKER,
            ApplicationExtensionObject::LABEL,
            ApplicationExtensionObject::BLOCKSIZE,
            ApplicationExtensionObject::NETSCAPE,
            ApplicationExtensionObject::SUB_BLOCKSIZE,
            ApplicationExtensionObject::BLOCK_INT,
            $this->encodeLoops(),
            ApplicationExtensionObject::TERMINATOR,
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
