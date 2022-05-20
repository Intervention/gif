<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\AbstractEntity;
use Intervention\Gif\ApplicationExtension;
use Intervention\Gif\DataSubBlock;
use Intervention\Gif\NetscapeApplicationExtension;

class ApplicationExtensionDecoder extends AbstractDecoder
{
    /**
     * Decode current source
     *
     * @return AbstractEntity
     */
    public function decode(): AbstractEntity
    {
        $result = new ApplicationExtension();

        $marker = $this->getNextByte();
        $label = $this->getNextByte();
        $blocksize = $this->decodeBlockSize($this->getNextByte());
        $application = $this->getNextBytes($blocksize);

        if ($application === NetscapeApplicationExtension::IDENTIFIER . NetscapeApplicationExtension::AUTH_CODE) {
            $result = new NetscapeApplicationExtension();

            // skip length
            $this->getNextByte();

            $result->setBlocks([new DataSubBlock($this->getNextBytes(3))]);

            // skip terminator
            $this->getNextByte();

            return $result;
        }

        $result->setApplication($application);

        // decode data sub blocks
        $blocksize = $this->decodeBlockSize($this->getNextByte());
        while ($blocksize > 0) {
            $result->addBlock(new DataSubBlock($this->getNextBytes($blocksize)));
            $blocksize = $this->decodeBlockSize($this->getNextByte());
        }

        return $result;
    }

    protected function decodeBlockSize(string $byte): int
    {
        return (int) @unpack('C', $byte)[1];
    }
}
