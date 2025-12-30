<?php

declare(strict_types=1);

namespace Intervention\Gif;

use ArrayIterator;
use GdImage;
use Intervention\Gif\Exceptions\CoreException;
use Intervention\Gif\Exceptions\DecoderException;
use Intervention\Gif\Exceptions\EncoderException;
use Intervention\Gif\Exceptions\FilePointerException;
use Intervention\Gif\Exceptions\InvalidArgumentException;
use Intervention\Gif\Exceptions\SplitterException;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<GifDataStream>
 */
class Splitter implements IteratorAggregate
{
    /**
     * Single frames resolved to GifDataStream
     *
     * @var array<GifDataStream>
     */
    protected array $frames = [];

    /**
     * Delays of each frame
     *
     * @var array<int>
     */
    protected array $delays = [];

    /**
     * Create new instance
     */
    public function __construct(protected GifDataStream $stream)
    {
        //
    }

    /**
     * Static constructor method
     */
    public static function create(GifDataStream $stream): self
    {
        return new self($stream);
    }

    /**
     * Decode raw data and create splitter in one step
     *
     * @throws InvalidArgumentException
     * @throws FilePointerException
     * @throws DecoderException
     */
    public static function decodeCreate(mixed $input): self
    {
        return new self(Decoder::decode($input));
    }

    /**
     * Iterator
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->frames);
    }

    /**
     * Get frames
     *
     * @return array<GifDataStream>
     */
    public function frames(): array
    {
        return $this->frames;
    }

    /**
     * Get delays
     *
     * @return array<int>
     */
    public function delays(): array
    {
        return $this->delays;
    }

    /**
     * Set stream of instance
     */
    public function setStream(GifDataStream $stream): self
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * Split current stream into array of seperate streams for each frame
     *
     * @throws SplitterException
     */
    public function split(): self
    {
        $this->frames = [];

        foreach ($this->stream->frames() as $frame) {
            // create separate stream for each frame
            try {
                $gif = Builder::canvas(
                    $this->stream->logicalScreenDescriptor()->width(),
                    $this->stream->logicalScreenDescriptor()->height()
                )->gifDataStream();
            } catch (InvalidArgumentException $e) {
                throw new SplitterException('Failed to create separate file pointer for each frame', previous: $e);
            }

            // check if working stream has global color table
            if ($table = $this->stream->globalColorTable()) {
                $gif->setGlobalColorTable($table);
                $gif->logicalScreenDescriptor()->setGlobalColorTableExistance(true);

                $gif->logicalScreenDescriptor()->setGlobalColorTableSorted(
                    $this->stream->logicalScreenDescriptor()->globalColorTableSorted()
                );

                $gif->logicalScreenDescriptor()->setGlobalColorTableSize(
                    $this->stream->logicalScreenDescriptor()->globalColorTableSize()
                );

                $gif->logicalScreenDescriptor()->setBackgroundColorIndex(
                    $this->stream->logicalScreenDescriptor()->backgroundColorIndex()
                );

                $gif->logicalScreenDescriptor()->setPixelAspectRatio(
                    $this->stream->logicalScreenDescriptor()->pixelAspectRatio()
                );

                $gif->logicalScreenDescriptor()->setBitsPerPixel(
                    $this->stream->logicalScreenDescriptor()->bitsPerPixel()
                );
            }

            // copy original frame
            $gif->addFrame($frame);

            $this->frames[] = $gif;
            $this->delays[] = match (is_object($frame->graphicControlExtension())) {
                true => $frame->graphicControlExtension()->delay(),
                default => 0,
            };
        }

        return $this;
    }

    /**
     * Return array of GD library resources for each frame
     *
     * @throws CoreException
     * @return array<GdImage>
     */
    public function toResources(): array
    {
        $resources = [];

        foreach ($this->frames as $frame) {
            try {
                $resource = imagecreatefromstring($frame->encode());
            } catch (EncoderException) {
                throw new CoreException('Failed to extract animation frames to resources');
            }

            if ($resource === false) {
                throw new CoreException('Failed to extract animation frames to resources');
            }

            imagepalettetotruecolor($resource);
            imagesavealpha($resource, true);

            $resources[] = $resource;
        }

        return $resources;
    }

    /**
     * Return array of coalesced GD library resources for each frame
     *
     * @throws SplitterException
     * @throws CoreException
     * @return array<GdImage>
     */
    public function coalesceToResources(): array
    {
        $resources = $this->toResources();

        // static gif files don't need to be coalesced
        if (count($resources) === 1) {
            return $resources;
        }

        $width = imagesx($resources[0]);
        $height = imagesy($resources[0]);
        $transparent = imagecolortransparent($resources[0]);

        foreach ($resources as $key => $resource) {
            // get meta data
            $gif = $this->frames[$key];
            $descriptor = $gif->firstFrame()?->imageDescriptor();
            $offset_x = $descriptor?->left() ?: 0;
            $offset_y = $descriptor?->top() ?: 0;
            $w = $descriptor?->width() ?: 0;
            $h = $descriptor?->height() ?: 0;

            if (in_array($this->disposalMethod($gif), [DisposalMethod::NONE, DisposalMethod::PREVIOUS])) {
                if ($key >= 1) {
                    // create normalized gd image
                    $canvas = imagecreatetruecolor($width, $height);

                    if ($canvas === false) {
                        throw new CoreException('Failed to create new image instance for animation frame #' . $key);
                    }

                    if (imagecolortransparent($resource) != -1) {
                        $transparent = imagecolortransparent($resource);
                    } else {
                        $transparent = imagecolorallocatealpha($resource, 255, 0, 255, 127);
                    }

                    if (!is_int($transparent)) {
                        throw new CoreException(
                            'Failed to allocate transparent color in animation frame #' . $key,
                        );
                    }

                    // fill with transparent
                    imagefill($canvas, 0, 0, $transparent);
                    imagecolortransparent($canvas, $transparent);
                    imagealphablending($canvas, true);

                    // insert last as base
                    imagecopy(
                        $canvas,
                        $resources[$key - 1],
                        0,
                        0,
                        0,
                        0,
                        $width,
                        $height
                    );

                    // insert resource
                    imagecopy(
                        $canvas,
                        $resource,
                        $offset_x,
                        $offset_y,
                        0,
                        0,
                        $w,
                        $h
                    );
                } else {
                    imagealphablending($resource, true);
                    $canvas = $resource;
                }
            } else {
                // create normalized gd image
                $canvas = imagecreatetruecolor($width, $height);
                if ($canvas === false) {
                    throw new CoreException('Failed to create new image instance for animation frame #' . $key);
                }

                if (imagecolortransparent($resource) != -1) {
                    $transparent = imagecolortransparent($resource);
                } else {
                    $transparent = imagecolorallocatealpha($resource, 255, 0, 255, 127);
                }

                if (!is_int($transparent)) {
                    throw new CoreException('Animation frames cannot be converted into resources');
                }

                // fill with transparent
                imagefill($canvas, 0, 0, $transparent);
                imagecolortransparent($canvas, $transparent);
                imagealphablending($canvas, true);

                // insert frame resource
                imagecopy(
                    $canvas,
                    $resource,
                    $offset_x,
                    $offset_y,
                    0,
                    0,
                    $w,
                    $h
                );
            }

            $resources[$key] = $canvas;
        }

        return $resources;
    }

    /**
     * Find and return disposal method of given gif data stream
     *
     * @throws SplitterException
     */
    private function disposalMethod(GifDataStream $gif): DisposalMethod
    {
        $disposalMethod = $gif->firstFrame()?->graphicControlExtension()?->disposalMethod();

        return $disposalMethod ?: throw new SplitterException('Failed to find disposal method in gif data stream');
    }
}
