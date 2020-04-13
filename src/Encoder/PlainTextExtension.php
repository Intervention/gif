<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\PlainTextExtension as PlainTextExtensionObject;

class PlainTextExtension extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param PlainTextExtensionObject $source
     */
    public function __construct(PlainTextExtensionObject $source)
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
        if ($data = $this->source->getData()) {
            return implode('', [
                PlainTextExtensionObject::MARKER,
                PlainTextExtensionObject::LABEL,
                $data,
                PlainTextExtensionObject::TERMINATOR,
            ]);
        }
        
        return '';
    }
}
