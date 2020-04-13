<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\CommentExtension as CommentExtensionObject;

class CommentExtension extends AbstractEncoder
{
    /**
     * Create new decoder instance
     *
     * @param CommentExtensionObject $source
     */
    public function __construct(CommentExtensionObject $source)
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
        return implode('', [
            CommentExtensionObject::MARKER,
            CommentExtensionObject::LABEL,
            $this->encodeComments(),
            CommentExtensionObject::TERMINATOR,
        ]);
    }

    /**
     * Encode comment blocks
     *
     * @return string
     */
    protected function encodeComments(): string
    {
        return implode('', array_map(function ($comment) {
            return pack('C', strlen($comment)).$comment;
        }, $this->source->getComments()));
    }
}
