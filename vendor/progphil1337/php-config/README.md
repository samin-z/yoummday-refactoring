# PHP-Config
Simple and fast configuration solution

## Installation

Install with composer:
```bash
$ composer require progphil1337/php-config
```

## Compatibility

`ProgPhil1337\Config` requires PHP 8.1 (or better).

## Usage


### Basic example
app.php

```php
use Progphil1337\Config\Config;

$config = Config::create(__DIR__ . DIRECTORY_SEPARATOR . 'config.yaml');

$config->map(function (mixed $value): mixed {
	if (str_starts_with($value, '%m')) {
		return 'Modified value';
	}

	return $value;
});

$config->setHierarchyOperator('::'); // :: is default

echo $config->get('HttpServer::ip');

```
