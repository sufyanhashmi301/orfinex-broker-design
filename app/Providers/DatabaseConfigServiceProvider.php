<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class DatabaseConfigServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // No need to put anything in register method for this case.
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Cache settings for 6 hours
        $settings = Cache::remember('mt5_db_credentials', 21600, function () {
            return [
                'host' => setting('database_host', 'mt5_db_credentials'),
                'port' => setting('database_port', 'mt5_db_credentials'),
                'database' => setting('database_name', 'mt5_db_credentials'),
                'username' => setting('database_username', 'mt5_db_credentials'),
                'password' => setting('database_password', 'mt5_db_credentials'),
            ];
        });
//dd($settings);
        // Set default values if settings are not found
        $host = $settings['host'] ?? env('MT5_DB_HOST', '127.0.0.1');
        $port = $settings['port'] ?? env('MT5_DB_PORT', '3306');
        $database = $settings['database'] ?? env('MT5_DB_DATABASE', 'forge');
        $username = $settings['username'] ?? env('MT5_DB_USERNAME', 'forge');
        $password = $settings['password'] ?? env('MT5_DB_PASSWORD', '');

        // Set the database configuration dynamically
        Config::set('database.connections.mt5_db', [
            'driver'    => 'mysql',
            'host'      => $host,
            'port'      => $port,
            'database'  => $database,
            'username'  => $username,
            'password'  => $password,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
            'options'   => [
                \PDO::ATTR_PERSISTENT => true, // Enable persistent connections
            ],
        ]);
    }
}
