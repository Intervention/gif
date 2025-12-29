<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

use Intervention\Gif\Blocks\Header;
use Intervention\Gif\Exceptions\DecoderException;

class HeaderDecoder extends AbstractDecoder
{
    /**
     * Decode current sourc
     */
    public function decode(): Header
    {
        $header = new Header();
        $header->setVersion($this->decodeVersion());

        return $header;
    }

    /**
     * Decode version string
     */
    protected function decodeVersion(): string
    {
        $parsed = (bool) preg_match("/^GIF(?P<version>[0-9]{2}[a-z])$/", $this->getNextBytesOrFail(6), $matches);

        if ($parsed === false) {
            throw new DecoderException('Failed to parse GIF file header');
        }

        return $matches['version'];
    }
}
