<?php

namespace TheBachtiarz\AdditionalAttribute;

use Illuminate\Support\ServiceProvider;
use TheBachtiarz\AdditionalAttribute\Interfaces\AdditionalAttributeInterface;

class AdditionalAttributeServiceProvider extends ServiceProvider
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
                __DIR__ . '/../config/' . AdditionalAttributeInterface::ADDITIONAL_ATTRIBUTE_CONFIG_NAME . '.php' => config_path(AdditionalAttributeInterface::ADDITIONAL_ATTRIBUTE_CONFIG_NAME . '.php'),
            ], 'thebachtiarz-additional-attribute-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'thebachtiarz-additional-attribute-migrations');
        }
    }
}
