# Intervention Gif

Native PHP GIF Encoder/Decoder.

[![Latest Version](https://img.shields.io/packagist/v/intervention/gif.svg)](https://packagist.org/packages/intervention/gif)
[![Build Status](https://travis-ci.org/Intervention/gif.png?branch=master)](https://travis-ci.org/Intervention/gif)
[![Monthly Downloads](https://img.shields.io/packagist/dm/intervention/gif.svg)](https://packagist.org/packages/intervention/gif/stats)

## Installation

You can install this package easily with [Composer](https://getcomposer.org/).

Just require the package with the following command:

    $ composer require intervention/gif

## Usage

### Decoding

```php
use Intervention\Gif\Decoder;

// Decode filepath to Intervention\Gif\GifDataStream::class
$gif = Decoder::decode('/images/animation.gif');

// Decoder can also handle binary content directly
$gif = Decoder::decode($contents);
```

### Encoding

Use the Builder class to create a new GIF image.

```php
use Intervention\Gif\Builder;

// create an empty canvas
// 
$width = 32;
$height = 32;
$loops = 0; // 0 for unlimited repetitions

// create new gif with width/height and optional
// number of repetitions of animation
$gif = Builder::canvas($width, $height, $loops);

// add animation frames to canvas
// 
$delay = .25; // delay in seconds after next frame is displayed
$left = 0; // position offset (left)
$top = 0; // position offset (top)

// add animation frames with optional delay in seconds
// and optional position offset for each frame
$gif->addFrame('/images/frame01.gif', $delay, $left, $top);
$gif->addFrame('/images/frame02.gif', $delay, $left);
$gif->addFrame('/images/frame03.gif', $delay);
$gif->addFrame('/images/frame04.gif');

// encode
$data = $gif->encode();
```

## License

Intervention Gif is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2020 [Oliver Vogel](http://olivervogel.com/)
