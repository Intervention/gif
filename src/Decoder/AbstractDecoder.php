<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\AbstractEntity;

abstract class AbstractDecoder
{
    /**
     * Source to decode
     *
     * @var string
     */
    protected $source;

    /**
     * File handle to access source
     *
     * @var resource
     */
    protected $handle;

    /**
     * Decode current source
     *
     * @return AbstractEntity
     */
    abstract public function decode(): AbstractEntity;

    /**
     * Create new instance
     *
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->source = $source;
        $this->handle = $this->getSourceHandle();
    }

    /**
     * Set source to decode
     *
     * @param string $source
     */
    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source file handle
     *
     * @return resource
     */
    protected function getSourceHandle()
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $this->source);
        rewind($handle);

        return $handle;
    }

    /**
     * Read given number of bytes and move file pointer
     *
     * @param  int $length
     * @return string
     */
    protected function getNextBytes(int $length): string
    {
        return fread($this->handle, $length);
    }

    /**
     * Read bytes until given char is found
     *
     * @param  string $char
     * @return string
     */
    protected function getNextBytesUntil($char): string
    {
        $bytes = '';
        do {
            $byte = $this->getNextBytes(1);
            $bytes .= $byte;
        } while ($byte !== $char);

        return $bytes;
    }

    /**
     * Close down instance
     */
    public function __destruct()
    {
        fclose($this->handle);
    }
}
