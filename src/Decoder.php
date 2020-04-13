<?php

namespace Intervention\Gif;

use Intervention\Gif\Decoder\Header as HeaderDecoder;
use Intervention\Gif\Decoder\LogicalScreenDescriptor as LogicalScreenDescriptorDecoder;
use Intervention\Gif\ImageDescriptor;

class Decoder
{
    /**
     * File pointer handle
     *
     * @var resource
     */
    protected $handle;
    public $pos;

    /**
     * Init decoder from image data
     *
     * @param  string $data
     * @return self
     */
    public function initFromData(string $data): self
    {
        $this->handle = fopen('php://memory', 'r+');

        fwrite($this->handle, $data);
        rewind($this->handle);

        return $this;
    }

    /**
     * Init decoder from path
     *
     * @param  string $path
     * @return self
     */
    public function initFromPath(string $path): self
    {
        $this->handle = fopen($path, 'rb');

        return $this;
    }

    /**
     * Decode image stream
     *
     * @return GifFormat
     */
    public function decode(): GifFormat
    {
        $gif = new GifFormat;

        // header
        /*
        $gif->setHeader(
            Header::create($this->getNextBytes(6))
        );

        // logical screen descriptor
        $gif->setLogicalScreenDescriptor(
            LogicalScreenDescriptor::create($this->getNextBytes(7))
        );
        */
       
        /*

        // global color table
        if ($gif->getLogicalScreenDescriptor()->getGlobalColorTableExistance()) {
            $size = $gif->getLogicalScreenDescriptor()->getGlobalColorTableByteSize();
            $gif->setGlobalColorTable(
                ColorTable::create($this->getNextBytes($size))
            );
        }

        // frames
        while (! feof($this->handle)) {
            $frame = new Frame;
            switch ($this->getNextBytes(1)) {
                case Extension::MARKER:
                    $frame->addExtension($this->decodeExtension());
                    break;

                case ImageDescriptor::SEPARATOR:
                    $descriptor = ImageDescriptor::create(ImageDescriptor::SEPARATOR.$this->getNextBytes(9));
                    $frame->setImageDescriptor($descriptor);
                    if ($descriptor->getLocalColorTableExistance()) {
                        $size = $descriptor->getLocalColorTableByteSize();
                        $frame->setLocalColorTable(
                            ColorTable::create($this->getNextBytes($size))
                        );
                    }
                    $frame->setImageData($this->decodeImageData());
                    break;

                case Trailer::MARKER:
                    break 2;

                default:
                    throw new Exception\DecoderException(
                        'Unable to decode GIF image ('.$this->pos.') .'
                    );
            }

            $gif->addFrame($frame);
        }
        */
        
        return $gif;
    }

    protected function decodeExtension(): AbstractExtension
    {
        $label = $this->getNextBytes(1);

        switch ($label) {
            case GraphicControlExtension::LABEL:
                return GraphicControlExtension::create(AbstractExtension::MARKER.$label.$this->getNextBytes(6));

            case ApplicationExtension::LABEL:
                return ApplicationExtension::create(AbstractExtension::MARKER.$label.$this->getNextBytes(17));

            case CommentExtension::LABEL:
                return CommentExtension::create(AbstractExtension::MARKER.$label.$this->getNextBytesUntil("\x00"));

            case PlainTextExtension::LABEL:
                return PlainTextExtension::create(AbstractExtension::MARKER.$label.$this->getNextBytesUntil("\x00"));

            default:
                throw new Exception\DecoderException(
                    'Not supported extension label ('.bin2hex($label).', '.$this->pos.') found.'
                );
        }
    }

    protected function decodeImageData()
    {
        $data = new ImageData;

        // LZW minimum code size
        $data->append($this->getNextBytes(1));

        do {
            $byte = $this->getNextBytes(1);
            $data->append($byte);
            if ($byte !== "\x00") {
                $size = unpack('C', $byte)[1];
                $data->append($this->getNextBytes($size));
            }
        } while ($byte !== "\x00");

        return $data;
    }

    /**
     * Read given number of bytes and move file pointer
     *
     * @param  int $length
     * @return string
     */
    protected function getNextBytes(int $length): string
    {
        $this->pos += $length;
        return fread($this->handle, $length);
    }

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
