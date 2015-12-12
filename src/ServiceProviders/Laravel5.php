<?php

namespace CodeZero\Twitter\ServiceProviders;

use Illuminate\Support\ServiceProvider;

class Laravel5 extends ServiceProvider
{
    /**
     * Bootstrap the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->setPublishPaths();
        $this->mergeConfig();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\CodeZero\Twitter\Twitter::class, function ($app) {
            $config = config("twitter");
            $configurator = new \CodeZero\Configurator\DefaultConfigurator();
            $courier = $app->make(\CodeZero\Twitter\TwitterCourier::class);
            $authHelper = new \CodeZero\Twitter\AuthHelper();
            $urlHelper = new \CodeZero\Utilities\UrlHelper();
            $twitterFactory = new \CodeZero\Twitter\TwitterFactory();

            return new \CodeZero\Twitter\Twitter($config, $configurator, $courier, $authHelper, $urlHelper, $twitterFactory);
        });
    }

    /**
     * Set publish paths.
     *
     * @return void
     */
    private function setPublishPaths()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('twitter.php')
        ], 'config');
    }

    /**
     * Merge configuration files.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'twitter');
    }
}
