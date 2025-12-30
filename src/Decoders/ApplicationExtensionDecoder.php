<?php

declare(strict_types=1);

namespace Intervention\Gif\Decoders;

use Intervention\Gif\Blocks\ApplicationExtension;
use Intervention\Gif\Blocks\DataSubBlock;
use Intervention\Gif\Blocks\NetscapeApplicationExtension;
use Intervention\Gif\Exceptions\DecoderException;

class ApplicationExtensionDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     */
    public function decode(): ApplicationExtension
    {
        $result = new ApplicationExtension();

        $this->nextByteOrFail(); // marker
        $this->nextByteOrFail(); // label
        $blocksize = $this->decodeBlockSize($this->nextByteOrFail());
        $application = $this->nextBytesOrFail($blocksize);

        if ($application === NetscapeApplicationExtension::IDENTIFIER . NetscapeApplicationExtension::AUTH_CODE) {
            $result = new NetscapeApplicationExtension();

            // skip length
            $this->nextByteOrFail();

            $result->setBlocks([
                new DataSubBlock(
                    $this->nextBytesOrFail(3)
                )
            ]);

            // skip terminator
            $this->nextByteOrFail();

            return $result;
        }

        $result->setApplication($application);

        // decode data sub blocks
        $blocksize = $this->decodeBlockSize($this->nextByteOrFail());
        while ($blocksize > 0) {
            $result->addBlock(new DataSubBlock($this->nextBytesOrFail($blocksize)));
            $blocksize = $this->decodeBlockSize($this->nextByteOrFail());
        }

        return $result;
    }

    /**
     * Decode block size of ApplicationExtension from given byte
     */
    protected function decodeBlockSize(string $byte): int
    {
        $unpacked = @unpack('C', $byte);

        if ($unpacked === false || !array_key_exists(1, $unpacked)) {
            throw new DecoderException('Failed to decode block size of application extension');
        }

        return intval($unpacked[1]);
    }
}
