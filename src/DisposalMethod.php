<?php

namespace Intervention\Gif;

class DisposalMethod
{
    public const UNDEFINED = 0;
    public const NONE = 1; // overlay each frame in sequence
    public const BACKGROUND = 2; // clear to background (as indicated by the logical screen descriptor)
    public const PREVIOUS = 3; // restore the canvas to its previous state
}
