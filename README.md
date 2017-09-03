Publisher Component for Orchestra Platform
==============


[![Build Status](https://travis-ci.org/orchestral/publisher.svg?branch=master)](https://travis-ci.org/orchestral/publisher)
[![Latest Stable Version](https://poser.pugx.org/orchestra/publisher/version)](https://packagist.org/packages/orchestra/publisher)
[![Total Downloads](https://poser.pugx.org/orchestra/publisher/downloads)](https://packagist.org/packages/orchestra/publisher)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/publisher/v/unstable)](//packagist.org/packages/orchestra/publisher)
[![License](https://poser.pugx.org/orchestra/publisher/license)](https://packagist.org/packages/orchestra/publisher)

## Table of Content

* [Version Compatibility](#version-compatibility)
* [Installation](#installation)
* [Configuration](#configuration)
* [Changelog](https://github.com/orchestral/publisher/releases)

## Version Compatibility

Laravel    | Publisher
:----------|:----------
 4.1.x     | 2.1.x
 4.2.x     | 2.2.x
 5.0.x     | 3.0.x
 5.1.x     | 3.1.x
 5.2.x     | 3.2.x
 5.3.x     | 3.3.x
 5.4.x     | 3.4.x
 5.5.x     | 3.5.x@dev

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "orchestra/publisher": "~3.0"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "orchestra/publisher=~3.0"

## Configuration

Add following service providers in `config/app.php`.

```php
'providers' => [

    // ...

    Orchestra\Publisher\PublisherServiceProvider::class,
    Orchestra\Publisher\CommandServiceProvider::class,
],
```
