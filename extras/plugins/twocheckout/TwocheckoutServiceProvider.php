<?php

namespace extras\plugins\twocheckout;

use Illuminate\Support\ServiceProvider;

class TwocheckoutServiceProvider extends ServiceProvider
{
	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('twocheckout', function ($app) {
			return new Twocheckout($app);
		});
	}
	
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Load plugin views
        $this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'payment');

        // Load plugin languages files
		$this->loadTranslationsFrom(realpath(__DIR__ . '/lang'), 'twocheckout');

        // Merge plugin config
        $this->mergeConfigFrom(realpath(__DIR__ . '/config.php'), 'payment');
    }
}
