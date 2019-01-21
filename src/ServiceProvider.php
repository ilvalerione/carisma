<?php

namespace Carisma;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Booting of services.
     *
     * @return void
     */
    public function boot()
    {
        /*if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/carisma.php', 'carisma');
        }*/

        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/carisma'),
        ], 'carisma-lang');*/
    }
	
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //Bind service in IoC container
        $this->app->singleton(\Carisma\Carisma::class, function($app){
            return new \Carisma\Carisma();
        });


        //$this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'carisma');

        // Artisan Commands
        $this->commands([
            \Carisma\Console\ResourceCommand::class,
            \Carisma\Console\FilterCommand::class,
            \Carisma\Console\ActionCommand::class,
        ]);
    }
}
