<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\PlainTextExtension;

class PlainTextExtensionDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return PlainTextExtension
     */
    public function decode(): PlainTextExtension
    {
        $extension = new PlainTextExtension;
        $extension->setData($this->decodeData());

        return $extension;
    }

    /**
     * Decode data from source
     *
     * @return string
     */
    protected function decodeData(): string
    {
        return substr(substr($this->source, 2), 0, -1);
    }
}
