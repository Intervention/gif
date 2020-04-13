<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\Header as HeaderObject;

class Header extends AbstractDecoder
{
    /**
     * Decode current sourc
     *
     * @return HeaderObject
     */
    public function decode(): HeaderObject
    {
        $header = new HeaderObject;
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
        $parsed = (bool) preg_match("/^GIF(?P<version>[0-9]{2}[a-z])$/", $this->source, $matches);

        if ($parsed === false) {
            throw new DecoderException('Unable to parse file header.');
        }

        return $matches['version'];
    }
}
