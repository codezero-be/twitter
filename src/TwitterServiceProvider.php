<?php namespace CodeZero\Twitter;

use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('codezero/twitter');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->registerConfigurator();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['twitter'];
	}

    /**
     * Register the configurator binding
     */
    private function registerConfigurator()
    {
        $this->app->bind('CodeZero\Twitter\Twitter', function($app)
        {
            $loader = new \CodeZero\Configurator\Loader();

            $config = $app['config']->has("twitter")
                ? $app['config']->get("twitter")
                : $app['config']->get("twitter::config");

            $configurator = new \CodeZero\Configurator\Configurator($loader, $config);

            $courier = $app->make('CodeZero\Twitter\TwitterCourier');

            $authHelper = new \CodeZero\Twitter\AuthHelper();
            $urlHelper = new \CodeZero\Utilities\UrlHelper();
            $twitterFactory = new \CodeZero\Twitter\TwitterFactory();

            return new \CodeZero\Twitter\Twitter($configurator, $courier, $authHelper, $urlHelper, $twitterFactory);
        });
    }

}
