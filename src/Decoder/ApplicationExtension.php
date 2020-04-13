<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\ApplicationExtension as ApplicationExtensionObject;

class ApplicationExtension extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return ApplicationExtensionObject
     */
    public function decode(): ApplicationExtensionObject
    {
        $result = new ApplicationExtensionObject;
        $result->setLoops($this->decodeLoops());

        return $result;
    }

    /**
     * Decode delay value
     *
     * @return int
     */
    protected function decodeLoops(): int
    {
        return unpack('v*', substr($this->source, 16, 2))[1];
    }
}
