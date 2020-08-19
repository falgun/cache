# Cache

A caching mechanism with multiple drivers. 

## Install
 *Please not that  PHP 7.4 or higher is required.*

Via Composer

``` bash
$ composer require falgunphp/cache
```

## Usage

``` php
<?php
use Falgun\Cache\Adapters\PhpOpcache;

$cacheDriver = new PhpOpcache('/path/to/cache/directory');

$cacheDriver->has('cache-key');

$cacheDriver->set('cache-key', 'cached value', 3600);

var_dump($cacheDriver->get('cache-key'));

//string(12) "cached value"
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
