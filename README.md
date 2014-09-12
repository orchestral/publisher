Orchestra Platform Publisher Component
==============

[![Latest Stable Version](https://poser.pugx.org/orchestra/publisher/v/stable.png)](https://packagist.org/packages/orchestra/publisher)
[![Total Downloads](https://poser.pugx.org/orchestra/publisher/downloads.png)](https://packagist.org/packages/orchestra/publisher)
[![Build Status](https://travis-ci.org/orchestral/publisher.svg?branch=master)](https://travis-ci.org/orchestral/publisher)
[![Coverage Status](https://coveralls.io/repos/orchestral/publisher/badge.png?branch=master)](https://coveralls.io/r/orchestral/publisher?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/orchestral/publisher/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/orchestral/publisher/)

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
'providers' => array(

	// ...

	'Orchestra\Publisher\PublisherServiceProvider',
),
```

## Resources

* [Documentation](http://orchestraplatform.com/docs/latest/components/publisher)
* [Change Log](http://orchestraplatform.com/docs/latest/components/publisher/changes#v3-0)
