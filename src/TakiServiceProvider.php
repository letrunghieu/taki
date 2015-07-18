<?php
/**
 * Created by PhpStorm.
 * User: Hieu Le
 * Date: 7/16/2015
 * Time: 2:23 PM
 */

namespace HieuLe\Taki;


use Illuminate\Support\ServiceProvider;

class TakiServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            dirname(dirname(__FILE__)) . '/config/taki.php' => config_path('taki.php'),
        ]);
        $this->mergeConfigFrom(
            dirname(dirname(__FILE__)) . '/config/taki.php',
            'taki'
        );

        $this->publishes([
            dirname(dirname(__FILE__)) . '/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('taki', function ($app) {
            return new Auth($app['auth']);
        });
    }
}