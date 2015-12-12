<?php

namespace CodeZero\Twitter;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    /**
     * Actual provider
     *
     * @var \Illuminate\Support\ServiceProvider
     */
    protected $provider;

    /**
     * Create a new service provider instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->provider = $this->getProvider();
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        return $this->provider->boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        return $this->provider->register();
    }

    /**
     * Return ServiceProvider according to Laravel version.
     */
    private function getProvider()
    {
        if (version_compare(app()->version(), '5.0', '<')) {
            $provider = '\CodeZero\Twitter\ServiceProviders\Laravel4';
        } else {
            $provider = '\CodeZero\Twitter\ServiceProviders\Laravel5';
        }

        return new $provider($this->app);
    }
}
