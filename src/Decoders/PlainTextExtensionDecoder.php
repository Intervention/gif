<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

use Intervention\Gif\Blocks\PlainTextExtension;
use Intervention\Gif\Exceptions\DecoderException;

class PlainTextExtensionDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @throws DecoderException
     * @return PlainTextExtension
     */
    public function decode(): PlainTextExtension
    {
        $extension = new PlainTextExtension();

        // skip marker & label
        $this->nextBytesOrFail(2);

        // skip info block
        $this->nextBytesOrFail($this->infoBlockSize());

        // text blocks
        $extension->setText($this->decodeTextBlocks());

        return $extension;
    }

    /**
     * Get number of bytes in header block
     *
     * @throws DecoderException
     * @return int
     */
    protected function infoBlockSize(): int
    {
        return unpack('C', $this->nextByteOrFail())[1];
    }

    /**
     * Decode text sub blocks
     *
     * @throws DecoderException
     * @return array<string>
     */
    protected function decodeTextBlocks(): array
    {
        $blocks = [];

        do {
            $char = $this->nextByteOrFail();
            $size = (int) unpack('C', $char)[1];
            if ($size > 0) {
                $blocks[] = $this->nextBytesOrFail($size);
            }
        } while ($char !== PlainTextExtension::TERMINATOR);

        return $blocks;
    }
}
