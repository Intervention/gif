<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\PlainTextExtension;

class PlainTextExtensionDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return PlainTextExtension
     */
    public function decode(): PlainTextExtension
    {
        $extension = new PlainTextExtension;

        // skip info block
        $this->getNextBytes($this->getInfoBlockSize());

        // text blocks
        $extension->setText($this->decodeTextBlocks());

        return $extension;
    }

    /**
     * Get number of bytes in header block
     *
     * @return int
     */
    protected function getInfoBlockSize(): int
    {
        $byte = $this->getNextByte(); // size byte, marker or label

        switch ($byte) {
            case PlainTextExtension::MARKER:
                $this->getNextByte(); // label
                $byte = $this->getNextByte(); // size byte
                break;

            case PlainTextExtension::LABEL:
                $byte = $this->getNextByte(); // size byte
                break;
        }

        return unpack('C', $byte)[1];
    }

    /**
     * Decode text sub blocks
     *
     * @return array
     */
    protected function decodeTextBlocks(): array
    {
        $blocks = [];

        do {
            $char = $this->getNextByte();
            $size = (int) unpack('C', $char)[1];
            if ($size > 0) {
                $blocks[] = $this->getNextBytes($size);
            }
        } while ($char !== PlainTextExtension::TERMINATOR);

        return $blocks;
    }
}
