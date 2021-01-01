<?php

namespace Intervention\Gif;

class Header extends AbstractEntity
{
    /**
     * Header signature
     */
    public const SIGNATURE = 'GIF';

    /**
     * Current GIF version
     */
    protected $version = '89a';

    /**
     * Set GIF version
     *
     * @param string $value
     */
    public function setVersion(string $value): self
    {
        $this->version = $value;

        return $this;
    }

    /**
     * Return current version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
