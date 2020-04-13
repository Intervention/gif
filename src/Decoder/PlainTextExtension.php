<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\PlainTextExtension as PlainTextExtensionObject;

class PlainTextExtension extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return PlainTextExtensionObject
     */
    public function decode(): PlainTextExtensionObject
    {
        $extension = new PlainTextExtensionObject;
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
