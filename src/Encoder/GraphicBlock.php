<?php

namespace Intervention\Gif\Encoder;

use Intervention\Gif\GraphicBlock as GraphicBlockObject;

class GraphicBlock extends AbstractEncoder
{
    /**
     * Create new instance
     *
     * @param GraphicBlockObject $source
     */
    public function __construct(GraphicBlockObject $source)
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
