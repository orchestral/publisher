Publisher Component for Orchestra Platform
==============

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/publisher.svg?style=flat)](https://packagist.org/packages/orchestra/publisher)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/publisher.svg?style=flat)](https://packagist.org/packages/orchestra/publisher)
[![MIT License](https://img.shields.io/packagist/l/orchestra/publisher.svg?style=flat)](https://packagist.org/packages/orchestra/publisher)
[![Build Status](https://img.shields.io/travis/orchestral/publisher/master.svg?style=flat)](https://travis-ci.org/orchestral/publisher)
[![Coverage Status](https://img.shields.io/coveralls/orchestral/publisher/master.svg?style=flat)](https://coveralls.io/r/orchestral/publisher?branch=master)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/publisher/master.svg?style=flat)](https://scrutinizer-ci.com/g/orchestral/publisher/)

## Quick Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/publisher": "3.0.*"
	}
}
```

Next add the following service provider in `app/config/app.php`.

```php
'providers' => [

	// ...

	'Orchestra\Publisher\PublisherServiceProvider',
    'Orchestra\Publisher\CommandServiceProvider',
],
```

## Resources

* [Documentation](http://orchestraplatform.com/docs/latest/components/publisher)
* [Change Log](http://orchestraplatform.com/docs/latest/components/publisher/changes#v3-0)
