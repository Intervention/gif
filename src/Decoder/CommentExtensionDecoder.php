<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\CommentExtension;

class CommentExtensionDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return CommentExtension
     */
    public function decode(): CommentExtension
    {
        $extension = new CommentExtension;
        foreach ($this->decodeComments() as $comment) {
            $extension->addComment($comment);
        }

        return $extension;
    }

    /**
     * Decode comment from current source
     *
     * @return array
     */
    protected function decodeComments(): array
    {
        $comments = [];
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $this->source);
        rewind($handle);
        fread($handle, 2); // MARKER & LABEL

        while (! feof($handle)) {
            $blocksize = (int) @unpack('C', fread($handle, 1))[1];
            if ($blocksize > 0) {
                $comments[] = fread($handle, $blocksize);
            }
        }
        
        fclose($handle);

        return $comments;
    }
}
