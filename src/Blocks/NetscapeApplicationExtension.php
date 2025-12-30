<?php

declare(strict_types=1);

namespace Intervention\Gif\Blocks;

use Intervention\Gif\Exceptions\BlockException;

class NetscapeApplicationExtension extends ApplicationExtension
{
    public const IDENTIFIER = "NETSCAPE";
    public const AUTH_CODE = "2.0";
    public const SUB_BLOCK_PREFIX = "\x01";

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->setApplication(self::IDENTIFIER . self::AUTH_CODE);
        $this->setBlocks([new DataSubBlock(self::SUB_BLOCK_PREFIX . "\x00\x00")]);
    }

    /**
     * Get number of loops
     */
    public function loops(): int
    {
        $unpacked = unpack('v*', substr($this->firstBlock()->value(), 1));

        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new BlockException('Failed to calculate loop count');
        }

        return $unpacked[1];
    }

    /**
     * Set number of loops
     */
    public function setLoops(int $loops): self
    {
        $this->setBlocks([
            new DataSubBlock(self::SUB_BLOCK_PREFIX . pack('v*', $loops))
        ]);

        return $this;
    }
}
