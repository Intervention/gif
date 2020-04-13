<?php

namespace Intervention\Gif;

abstract class AbstractExtension extends AbstractEntity
{
    const MARKER = "\x21";
    const TERMINATOR = "\x00";
}
