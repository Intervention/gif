<?php

namespace Intervention\Gif;

class FrameBlock extends AbstractEntity
{
    protected ?GraphicControlExtension $graphicControlExtension = null;
    protected ImageDescriptor $imageDescriptor;
    protected ?ColorTable $colorTable = null;
    protected ImageData $imageData;
    protected ?PlainTextExtension $plainTextExtension = null;
    protected array $applicationExtensions = [];
    protected array $commentExtensions = [];

    public function __construct()
    {
        $this->imageDescriptor = new ImageDescriptor();
        $this->imageData = new ImageData();
    }

    public function setGraphicControlExtension(GraphicControlExtension $extension): self
    {
        $this->graphicControlExtension = $extension;

        return $this;
    }

    public function getGraphicControlExtension(): ?GraphicControlExtension
    {
        return $this->graphicControlExtension;
    }

    public function setImageDescriptor(ImageDescriptor $descriptor): self
    {
        $this->imageDescriptor = $descriptor;
        return $this;
    }

    public function getImageDescriptor(): ImageDescriptor
    {
        return $this->imageDescriptor;
    }

    public function setColorTable(ColorTable $table): self
    {
        $this->colorTable = $table;

        return $this;
    }

    public function getColorTable(): ?ColorTable
    {
        return $this->colorTable;
    }

    public function setImageData(ImageData $data): self
    {
        $this->imageData = $data;

        return $this;
    }

    public function getImageData(): ImageData
    {
        return $this->imageData;
    }

    public function setPlainTextExtension(PlainTextExtension $extension): self
    {
        $this->plainTextExtension = $extension;

        return $this;
    }

    public function getPlainTextExtension(): ?PlainTextExtension
    {
        return $this->plainTextExtension;
    }

    public function addApplicationExtension(ApplicationExtension $extension): self
    {
        $this->applicationExtensions[] = $extension;

        return $this;
    }

    public function addCommentExtension(CommentExtension $extension): self
    {
        $this->commentExtensions[] = $extension;

        return $this;
    }

    public function getNetscapeExtension(): ?NetscapeApplicationExtension
    {
        $extensions = array_filter($this->applicationExtensions, function ($extension) {
            return $extension instanceof NetscapeApplicationExtension;
        });

        return count($extensions) ? reset($extensions) : null;
    }
}
