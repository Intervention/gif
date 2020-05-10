<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\SpecialPurposeBlock;

class CommentExtension extends AbstractExtension implements SpecialPurposeBlock
{
    public const LABEL = "\xFE";

    /**
     * Comment blocks
     *
     * @var array
     */
    protected $comments = [];

    /**
     * Get all or one comment
     *
     * @param  null|int $num
     * @return mixed
     */
    public function getComments(?int $num = null)
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
