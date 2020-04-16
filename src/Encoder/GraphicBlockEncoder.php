<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\GraphicBlock as GraphicBlock;

class GraphicBlockEncoder extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param GraphicBlock $source
     */
    public function __construct(GraphicBlock $source)
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
            $this->encodeGraphicControlExtension(),
            $this->source->getGraphicRenderingBlock()->encode(),
        ]);
    }

    /**
     * Encode graphic control extension from current source
     *
     * @return string
     */
    protected function encodeGraphicControlExtension(): string
    {
        $extension = $this->source->getGraphicControlExtension();

        return $extension ? $extension->encode() : '';
    }
}
