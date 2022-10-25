<?php

namespace TheBachtiarz\AdditionalAttribute;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use TheBachtiarz\AdditionalAttribute\Interfaces\Config\AdditionalAttributeConfigInterface;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register module additional attribute
     *
     * @return void
     */
    public function register(): void
    {
        // register

        if ($this->app->runningInConsole()) {
            $this->commands([]);
        }
    }

    /**
     * Boot module additional attribute
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/' . AdditionalAttributeConfigInterface::ADDITIONAL_ATTRIBUTE_CONFIG_NAME . '.php' => config_path(AdditionalAttributeConfigInterface::ADDITIONAL_ATTRIBUTE_CONFIG_NAME . '.php'),
            ], 'thebachtiarz-additional-attribute-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'thebachtiarz-additional-attribute-migrations');
        }
    }
}
