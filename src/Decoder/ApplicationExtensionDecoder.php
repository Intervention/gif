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

        // parse loop count
        $result->setLoops($this->decodeLoops(
            $this->getLoopBytes()
        ));

        // skip block terminator
        $this->getNextByte();

        return $result;
    }

    /**
     * Decode delay value
     *
     * @return int
     */
    protected function decodeLoops(string $bytes): int
    {
        return unpack('v*', $bytes)[1];
    }

    /**
     * Get loop bytes
     *
     * @return string
     */
    private function getLoopBytes(): string
    {
        $this->getNextBytes(16); // skip 16 bytes
        
        return $this->getNextBytes(2);
    }
}
