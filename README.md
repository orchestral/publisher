Publisher Component for Orchestra Platform
==============

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/publisher.svg?style=flat-square)](https://packagist.org/packages/orchestra/publisher)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/publisher.svg?style=flat-square)](https://packagist.org/packages/orchestra/publisher)
[![MIT License](https://img.shields.io/packagist/l/orchestra/publisher.svg?style=flat-square)](https://packagist.org/packages/orchestra/publisher)
[![Build Status](https://img.shields.io/travis/orchestral/publisher/master.svg?style=flat-square)](https://travis-ci.org/orchestral/publisher)
[![Coverage Status](https://img.shields.io/coveralls/orchestral/publisher/master.svg?style=flat-square)](https://coveralls.io/r/orchestral/publisher?branch=master)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/publisher/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/orchestral/publisher/)

## Table of Content

* [Version Compatibility](#version-compatibility)
* [Installation](#installation)
* [Configuration](#configuration)
* [Resources](#resources)
* [Change Log](http://orchestraplatform.com/docs/latest/components/publisher/changes#v3-4)

## Version Compatibility

Laravel    | Publisher
:----------|:----------
 4.1.x     | 2.1.x
 4.2.x     | 2.2.x
 5.0.x     | 3.0.x
 5.1.x     | 3.1.x
 5.2.x     | 3.2.x
 5.3.x     | 3.3.x

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

## Resources

* [Documentation](http://orchestraplatform.com/docs/latest/components/publisher)
