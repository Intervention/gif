<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\PlainTextExtension as PlainTextExtension;

class PlainTextExtensionEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param PlainTextExtension $source
     */
    public function __construct(PlainTextExtension $source)
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
                PlainTextExtension::MARKER,
                PlainTextExtension::LABEL,
                $data,
                PlainTextExtension::TERMINATOR,
            ]);
        }
        
        return '';
    }
}
