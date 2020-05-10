<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\SpecialPurposeBlock;

class ApplicationExtension extends AbstractExtension implements SpecialPurposeBlock
{
    protected const LABEL = "\xFF";
    protected const BLOCKSIZE = "\x0b";
    protected const IDENT = "NETSCAPE";
    protected const AUTH = "2.0";
    protected const SUB_BLOCKSIZE = "\x03";
    protected const BLOCK_INT = "\x01";

    /**
     * Currenty number of loops
     *
     * @var integer
     */
    protected $loops = 0;

    /**
     * Get number of loops
     *
     * @return int
     */
    public function getLoops(): int
    {
        return $this->loops;
    }

    /**
     * Set number of loops
     *
     * @param int $count
     */
    public function setLoops(int $count): self
    {
        $this->loops = $count;

        return $this;
    }
}
