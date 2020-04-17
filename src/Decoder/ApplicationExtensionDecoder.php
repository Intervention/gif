<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\Exception\DecoderException;

class ApplicationExtensionDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return AbstractEntity
     */
    public function decode(): AbstractEntity
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
