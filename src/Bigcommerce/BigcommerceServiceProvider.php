<?php

namespace VerveCommerce\Bigcommerce;

use Config;
use Illuminate\Support\ServiceProvider;

class BigcommerceServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/bigcommerce.php' => config_path('bigcommerce.php'),
        ]);

        $this->app->alias('Bigcommerce', 'VerveCommerce\Bigcommerce\Facades\Bigcommerce');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bigcommerce', function ($app) {
            return new Bigcommerce();
        });
    }
}
