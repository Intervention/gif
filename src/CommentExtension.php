<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\SpecialPurposeBlock;

class CommentExtension extends AbstractExtension implements SpecialPurposeBlock
{
    const LABEL = "\xFE";

    /**
     * Comment blocks
     *
     * @var string
     */
    protected $comments = [];

    /**
     * Get all or one comment
     *
     * @param  int number of comment block
     * @return mixed
     */
    public function getComments($num = null)
    {
        if (is_null($num)) {
            return $this->comments;
        }

        return array_key_exists($num, $this->comments) ? $this->comments[$num] : null;
    }

    /**
     * Set comment text
     *
     * @param string $value
     */
    public function addComment(string $value): self
    {
        $this->comments[] = $value;

        return $this;
    }
}
