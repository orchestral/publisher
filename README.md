Orchestra Platform 2 Publisher Component
==============

[![Latest Stable Version](https://poser.pugx.org/orchestra/publisher/v/stable.png)](https://packagist.org/packages/orchestra/publisher) 
[![Total Downloads](https://poser.pugx.org/orchestra/publisher/downloads.png)](https://packagist.org/packages/orchestra/publisher) 
[![Build Status](https://travis-ci.org/orchestral/publisher.svg?branch=2.1)](https://travis-ci.org/orchestral/publisher) 
[![Coverage Status](https://coveralls.io/repos/orchestral/publisher/badge.png?branch=2.1)](https://coveralls.io/r/orchestral/publisher?branch=2.1) 
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/orchestral/publisher/badges/quality-score.png?b=2.1)](https://scrutinizer-ci.com/g/orchestral/publisher/) 

## Quick Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/publisher": "2.1.*"
	}
}
```

Next add the following service provider in `app/config/app.php`.

```php
'providers' => array(

	// ...

	'Orchestra\Publisher\PublisherServiceProvider',
),
```

## Resources

* [Documentation](http://orchestraplatform.com/docs/latest/components/publisher)
* [Change Log](http://orchestraplatform.com/docs/latest/components/publisher/changes#v2-1)
