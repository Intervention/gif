<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\TableBasedImage as TableBasedImage;

class TableBasedImageEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param TableBasedImage $source
     */
    public function __construct(TableBasedImage $source)
    {
        $this->source = $source;
    }

    /**
     * Encode current source
     *
     * @return string
     */
    public function encode(): string
    {
        return implode('', [
            $this->source->getDescriptor()->encode(),
            $this->encodeLocalColorTable(),
            $this->source->getData()->encode()
        ]);
    }

    /**
     * Encode local color table if available
     *
     * @return string
     */
    protected function encodeLocalColorTable(): string
    {
        $table = $this->source->getColorTable();

        return $table ? $table->encode() : '';
    }
}
