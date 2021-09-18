<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse;

use ChurakovMike\LaravelClickHouse\Database\Connection;
use ChurakovMike\LaravelClickHouse\Database\Model;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\ServiceProvider;

class ClickhouseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /** @var DatabaseManager $db */
        $db = $this->app->get('db');

        $db->extend('clickhouse', function ($config, $name) {
            $config['name'] = $name;

            return new Connection($config);
        });

        Model::setConnectionResolver($db);
    }
}
