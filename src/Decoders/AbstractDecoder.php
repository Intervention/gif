<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

use Intervention\Gif\Exceptions\DecoderException;
use Intervention\Gif\Exceptions\InvalidArgumentException;

abstract class AbstractDecoder
{
    /**
     * Decode current source
     */
    abstract public function decode(): mixed;

    /**
     * Create new instance
     */
    public function __construct(protected mixed $filePointer, protected ?int $length = null)
    {
        //
    }

    /**
     * Set source to decode
     */
    public function setFilePointer(mixed $filePointer): self
    {
        $this->filePointer = $filePointer;

        return $this;
    }

    /**
     * Read given number of bytes and move file pointer
     */
    protected function nextBytesOrFail(int $length): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException('The length of the next byte chain must be at least one byte');
        }

        $bytes = fread($this->filePointer, $length);
        if ($bytes === false || strlen($bytes) !== $length) {
            throw new DecoderException('Unexpected end of file');
        }

        return $bytes;
    }

    /**
     * Read given number of bytes and move pointer back to previous position
     */
    protected function viewNextBytesOrFail(int $length): string
    {
        $bytes = $this->nextBytesOrFail($length);
        $this->movePointer($length * -1);

        return $bytes;
    }

    /**
     * Read next byte and move pointer back to previous position
     */
    protected function viewNextByteOrFail(): string
    {
        return $this->viewNextBytesOrFail(1);
    }

    /**
     * Read all remaining bytes from file pointer
     */
    protected function remainingBytes(): string
    {
        $all = '';
        do {
            $byte = fread($this->filePointer, 1);
            $all .= $byte;
        } while (!feof($this->filePointer));

        return $all;
    }

    /**
     * Get next byte in stream and move file pointer
     */
    protected function nextByteOrFail(): string
    {
        return $this->nextBytesOrFail(1);
    }

    /**
     * Move file pointer on file pointer by given offset
     */
    protected function movePointer(int $offset): self
    {
        fseek($this->filePointer, $offset, SEEK_CUR);

        return $this;
    }

    /**
     * Decode multi byte value
     */
    protected function decodeMultiByte(string $bytes): int
    {
        $unpacked = unpack('v*', $bytes);

        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new DecoderException('Failed to decode given bytes');
        }

        return $unpacked[1];
    }

    /**
     * Set length
     */
    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     */
    public function length(): ?int
    {
        return $this->length;
    }

    /**
     * Get current file pointer position
     */
    public function position(): int
    {
        $position = ftell($this->filePointer);

        if ($position === false) {
            throw new DecoderException('Failed to read current position from file pointer');
        }

        return $position;
    }
}
