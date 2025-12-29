<?php

declare(strict_types=1);

namespace Intervention\Gif;

use ArrayIterator;
use GdImage;
use Intervention\Gif\Exceptions\CoreException;
use Intervention\Gif\Exceptions\GifException;
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
    public function getFrames(): array
    {
        return $this->frames;
    }

    /**
     * Get delays
     *
     * @return array<int>
     */
    public function getDelays(): array
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
     */
    public function split(): self
    {
        $this->frames = [];

        foreach ($this->stream->getFrames() as $frame) {
            // create separate stream for each frame
            $gif = Builder::canvas(
                $this->stream->getLogicalScreenDescriptor()->getWidth(),
                $this->stream->getLogicalScreenDescriptor()->getHeight()
            )->getGifDataStream();

            // check if working stream has global color table
            if ($this->stream->hasGlobalColorTable()) {
                $gif->setGlobalColorTable($this->stream->getGlobalColorTable());
                $gif->getLogicalScreenDescriptor()->setGlobalColorTableExistance(true);

                $gif->getLogicalScreenDescriptor()->setGlobalColorTableSorted(
                    $this->stream->getLogicalScreenDescriptor()->getGlobalColorTableSorted()
                );

                $gif->getLogicalScreenDescriptor()->setGlobalColorTableSize(
                    $this->stream->getLogicalScreenDescriptor()->getGlobalColorTableSize()
                );

                $gif->getLogicalScreenDescriptor()->setBackgroundColorIndex(
                    $this->stream->getLogicalScreenDescriptor()->getBackgroundColorIndex()
                );

                $gif->getLogicalScreenDescriptor()->setPixelAspectRatio(
                    $this->stream->getLogicalScreenDescriptor()->getPixelAspectRatio()
                );

                $gif->getLogicalScreenDescriptor()->setBitsPerPixel(
                    $this->stream->getLogicalScreenDescriptor()->getBitsPerPixel()
                );
            }

            // copy original frame
            $gif->addFrame($frame);

            $this->frames[] = $gif;
            $this->delays[] = match (is_object($frame->getGraphicControlExtension())) {
                true => $frame->getGraphicControlExtension()->getDelay(),
                default => 0,
            };
        }

        return $this;
    }

    /**
     * Return array of GD library resources for each frame
     *
     * @return array<GdImage>
     */
    public function toResources(): array
    {
        $resources = [];

        foreach ($this->frames as $frame) {
            $resource = imagecreatefromstring($frame->encode());

            if ($resource === false) {
                throw new CoreException('Failed to extract animation frames');
            }

            $result = imagepalettetotruecolor($resource);

            if ($result === false) {
                throw new CoreException('Failed to transform animation frames to truecolor');
            }

            $result = imagesavealpha($resource, true);

            if ($result === false) {
                throw new CoreException('Failed to set alpha channel flag on animation frames');
            }

            $resources[] = $resource;
        }

        return $resources;
    }

    /**
     * Return array of coalesced GD library resources for each frame
     *
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
            $descriptor = $gif->getFirstFrame()->getImageDescriptor();
            $offset_x = $descriptor->getLeft();
            $offset_y = $descriptor->getTop();
            $w = $descriptor->getWidth();
            $h = $descriptor->getHeight();

            if (in_array($this->getDisposalMethod($gif), [DisposalMethod::NONE, DisposalMethod::PREVIOUS])) {
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
                    $result = imagefill($canvas, 0, 0, $transparent);
                    if ($result === false) {
                        throw new CoreException('Failed to fill frame #' . $key . ' with transparency');
                    }

                    imagecolortransparent($canvas, $transparent);

                    $result = imagealphablending($canvas, true);
                    if ($result === false) {
                        throw new CoreException('Failed to set alpha blending mode on frame #' . $key);
                    }

                    // insert last as base
                    $result = imagecopy(
                        $canvas,
                        $resources[$key - 1],
                        0,
                        0,
                        0,
                        0,
                        $width,
                        $height
                    );

                    if ($result === false) {
                        throw new CoreException('Failed to copy frame #' . $key);
                    }

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

                    if ($result === false) {
                        throw new CoreException('Failed to copy frame #' . $key);
                    }
                } else {
                    $result = imagealphablending($resource, true);
                    if ($result === false) {
                        throw new CoreException('Failed to set alpha blending mode on frame #' . $key);
                    }
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
                    throw new GifException('Animation frames cannot be converted into resources');
                }

                // fill with transparent
                $result = imagefill($canvas, 0, 0, $transparent);
                if ($result === false) {
                    throw new CoreException('Failed to fill frame #' . $key . ' with transparency');
                }

                imagecolortransparent($canvas, $transparent);

                $result = imagealphablending($canvas, true);
                if ($result === false) {
                    throw new CoreException('Failed to set alpha blending mode on frame #' . $key);
                }

                // insert frame resource
                $result = imagecopy(
                    $canvas,
                    $resource,
                    $offset_x,
                    $offset_y,
                    0,
                    0,
                    $w,
                    $h
                );

                if ($result === false) {
                    throw new CoreException('Failed to copy frame #' . $key);
                }
            }

            $resources[$key] = $canvas;
        }

        return $resources;
    }

    /**
     * Find and return disposal method of given gif data stream
     */
    private function getDisposalMethod(GifDataStream $gif): DisposalMethod
    {
        return $gif->getFirstFrame()->getGraphicControlExtension()->getDisposalMethod();
    }
}
