Laravel Clickhouse 
========================================
[![Maintainability](https://api.codeclimate.com/v1/badges/ec71cf6deea85aed1e6c/maintainability)](https://codeclimate.com/github/Churakovmike/laravel-clickhouse/maintainability)
[![StyleCI](https://github.styleci.io/repos/393719684/shield?style=flat&branch=main)](https://github.styleci.io/repos/393719684/shield?style=flat&branch=main)
[![License](http://poser.pugx.org/churakovmike/laravel-clickhouse/license)](https://packagist.org/packages/churakovmike/laravel-clickhouse)
[![Latest Stable Version](http://poser.pugx.org/churakovmike/laravel-clickhouse/v)](https://packagist.org/packages/churakovmike/laravel-clickhouse)
[![PHP Version Require](http://poser.pugx.org/churakovmike/laravel-clickhouse/require/php)](https://packagist.org/packages/churakovmike/laravel-clickhouse)
![PSALM](https://img.shields.io/github/workflow/status/churakovmike/laravel-clickhouse/Psalm?label=Psalm)
----------------------------------------------------------------------------------------------------------------------
Requirements
------------
+ laravel 7+
+ php 7.4+

Install
------------
```php 
composer require churakovmike/laravel-clickhouse
```

If you need the latest version
```php
composer require churakovmike/laravel-clickhouse:dev-main
```

Integrations
------------
In `config/app.php` add:
```php
    'providers' => [
        ...
        \ChurakovMike\LaravelClickHouse\ClickhouseServiceProvider::class,
        ...
    ]
```

Connection configures via `config/database.php`

Clickhouse default configuration example:
```php
'connections' => [
    'clickhouse' => [
        'driver' => 'clickhouse',
        'host' => 'http://127.0.0.1',
        'port' => 8123,
        'database' => 'database_name',
        'username' => 'default',
        'password' => '',
        'options' => [
            'timeout' => 10,
        ]
    ]
]
```
Usage
------------
Create new own model and inherit from the new model

```php
<?php

namespace App;

use ChurakovMike\LaravelClickHouse\Database\Model;

class Events extends Model
{
    // ...
}
```
That's all, you can work with clickhouse using the familiar Eloquent model.

### Following features coming soon

- Clusters switching
- Server switching
- Native clickhouse functions for the model (sumIf, countIf, quantilesTimingIf, argMinIf and etc.)
- New migration manager and new Blueprint for Clickhouse
- New data types for queries
