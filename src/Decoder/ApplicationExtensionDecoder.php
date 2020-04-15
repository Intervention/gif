<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\ApplicationExtension;

class ApplicationExtensionDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return ApplicationExtension
     */
    public function decode(): ApplicationExtension
    {
        $result = new ApplicationExtension;
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
