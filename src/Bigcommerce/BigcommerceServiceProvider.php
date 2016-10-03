<?php

namespace Oseintow\Bigcommerce;

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

        $this->app->alias('Bigcommerce', 'Oseintow\Bigcommerce\Facades\Bigcommerce');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
         $this->app['bigcommerce'] = $this->app->share(function($app)
         {
             return new Bigcommerce();
         });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
