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

// Decodes filepath to Intervention\Gif\GifDataStream::class
$gif = Decoder::decode('/images/animation.gif');

// Decoder can also handle binary content directly
$gif = Decoder::decode($contents);
```

## License

Intervention Gif is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2020 [Oliver Vogel](http://olivervogel.com/)