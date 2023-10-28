<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\Header;

class HeaderDecoder extends AbstractDecoder
{
    /**
     * Decode current sourc
     *
     * @return Header
     */
    public function decode(): Header
    {
        $header = new Header();
        $header->setVersion($this->decodeVersion());

        return $header;
    }

    /**
     * Decode version string
     *
     * @return string
     */
    protected function decodeVersion(): string
    {
        $parsed = (bool) preg_match("/^GIF(?P<version>[0-9]{2}[a-z])$/", $this->getNextBytes(6), $matches);

        if ($parsed === false) {
            throw new DecoderException('Unable to parse file header.');
        }

        return $matches['version'];
    }
}
