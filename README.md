# Intervention Gif

Native PHP GIF Encoder/Decoder.

[![Latest Version](https://img.shields.io/packagist/v/intervention/gif.svg)](https://packagist.org/packages/intervention/gif)
![build](https://github.com/Intervention/gif/actions/workflows/build.yml/badge.svg)
[![Monthly Downloads](https://img.shields.io/packagist/dm/intervention/gif.svg)](https://packagist.org/packages/intervention/gif/stats)

## Installation

You can install this package easily with [Composer](https://getcomposer.org/).

Just require the package with the following command:

```bash
composer require intervention/gif
```

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

## Development & Testing

With this package comes a Docker image to build a test suite and analysis
container. To build this container you have to have Docker installed on your
system. You can run all tests with this command.

```bash
docker-compose run --rm --build tests
```

Run the static analyzer on the code base.

```bash
docker-compose run --rm --build analysis
```
## License

Intervention Gif is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2020 [Oliver Vogel](http://intervention.io/)
