<?php

namespace Intervention\Gif;

use Intervention\Gif\Contracts\DataBlock;

class GifDataStream extends AbstractEntity
{
    /**
     * File header
     *
     * @var Header
     */
    protected $header;
    
    /**
     * Logical Screen
     *
     * @var LogicalScreen
     */
    protected $logicalScreen;
    
    /**
     * Array of data blocks
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->header = new Header;
        $this->logicalScreen = new LogicalScreen;
    }

    /**
     * Get header
     *
     * @return Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * Set header
     *
     * @param Header $header
     */
    public function setHeader(Header $header): self
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get logical screen
     *
     * @return LogicalScreen
     */
    public function getLogicalScreen(): LogicalScreen
    {
        return $this->logicalScreen;
    }

    /**
     * Set logical screen
     *
     * @param LogicalScreen $screen
     */
    public function setLogicalScreen(LogicalScreen $screen): self
    {
        $this->logicalScreen = $screen;

        return $this;
    }

    /**
     * Get array of data blocks
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Add to data
     *
     * @param DataBlock $block
     */
    public function addData(DataBlock $block): self
    {
        $this->data[] = $block;
        
        return $this;
    }

    /**
     * Set the current data
     *
     * @param array $data
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get trailer
     *
     * @return Trailer
     */
    public function getTrailer(): Trailer
    {
        return new Trailer;
    }
}
