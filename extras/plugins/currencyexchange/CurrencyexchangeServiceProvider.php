<?php

namespace extras\plugins\currencyexchange;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class CurrencyexchangeServiceProvider extends ServiceProvider
{
	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('currencyexchange', function ($app) {
			return new Currencyexchange($app);
		});
	}
	
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Merge plugin config
        $this->mergeConfigFrom(realpath(__DIR__ . '/config.php'), 'currencyexchange');
        
        // Load plugin views
        $this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'currencyexchange');
        
        // Load plugin languages files
        $this->loadTranslationsFrom(realpath(__DIR__ . '/lang'), 'currencyexchange');
        
        $this->registerCurrencyExchangeMiddleware($this->app->router);
        
        // Update the config vars
        $this->setConfigVars();
    }
    
    public function registerCurrencyExchangeMiddleware(Router $router)
    {
        // in Laravel 5.4+
        if (method_exists($router, 'aliasMiddleware')) {
            Route::aliasMiddleware('currencies', \extras\plugins\currencyexchange\app\Http\Middleware\Currencies::class);
            Route::aliasMiddleware('currencyExchange', \extras\plugins\currencyexchange\app\Http\Middleware\CurrencyExchange::class);
        } // in Laravel 5.3 and below
        else {
            Route::middleware('currencies', \extras\plugins\currencyexchange\app\Http\Middleware\Currencies::class);
            Route::middleware('currencyExchange', \extras\plugins\currencyexchange\app\Http\Middleware\CurrencyExchange::class);
        }
    }
    
    /**
     * Update the config vars
     */
    private function setConfigVars()
    {
        // Currency Exchange
		config()->set('currencyexchange.default', env('CURRENCY_EXCHANGE_DRIVER', config('settings.currencyexchange.driver')));
	
		if (config('currencyexchange.default') == 'currencylayer') {
            config()->set('currencyexchange.drivers.currencylayer.accessKey', env('CURRENCYLAYER_ACCESS_KEY', config('settings.currencyexchange.currencylayer_access_key')));
			config()->set('currencyexchange.drivers.currencylayer.currencyBase', env('CURRENCYLAYER_BASE', config('settings.currencyexchange.currencylayer_base')));
			config()->set('currencyexchange.drivers.currencylayer.pro', env('CURRENCYLAYER_PRO', config('settings.currencyexchange.currencylayer_pro')));
        }
	
		if (config('currencyexchange.default') == 'exchangerate_api') {
            config()->set('currencyexchange.drivers.exchangerate_api.apiKey', env('EXCHANGERATE_API_KEY', config('settings.currencyexchange.exchangerate_api_api_key')));
            config()->set('currencyexchange.drivers.exchangerate_api.currencyBase', env('EXCHANGERATE_API_BASE', config('settings.currencyexchange.exchangerate_api_base')));
        }
		
		if (config('currencyexchange.default') == 'exchangeratesapi_io') {
			config()->set(
				'currencyexchange.drivers.exchangeratesapi_io.accessKey',
				env('EXCHANGERATESAPI_IO_ACCESS_KEY', config('settings.currencyexchange.exchangeratesapi_io_access_key'))
			);
			config()->set(
				'currencyexchange.drivers.exchangeratesapi_io.currencyBase',
				env('EXCHANGERATESAPI_IO_BASE', config('settings.currencyexchange.exchangeratesapi_io_base'))
			);
			config()->set(
				'currencyexchange.drivers.exchangeratesapi_io.pro',
				env('EXCHANGERATESAPI_IO_PRO', config('settings.currencyexchange.exchangeratesapi_io_pro'))
			);
		}
	
		if (config('currencyexchange.default') == 'openexchangerates') {
            config()->set('currencyexchange.drivers.openexchangerates.appId', env('OPENEXCHANGERATES_APP_ID', config('settings.currencyexchange.openexchangerates_app_id')));
            config()->set('currencyexchange.drivers.openexchangerates.currencyBase', env('OPENEXCHANGERATES_BASE', config('settings.currencyexchange.openexchangerates_base')));
        }
	
		if (config('currencyexchange.default') == 'fixer_io') {
            config()->set('currencyexchange.drivers.fixer_io.accessKey', env('FIXER_IO_ACCESS_KEY', config('settings.currencyexchange.fixer_io_access_key')));
			config()->set('currencyexchange.drivers.fixer_io.currencyBase', env('FIXER_IO_BASE', config('settings.currencyexchange.fixer_io_base')));
			config()->set('currencyexchange.drivers.fixer_io.pro', env('FIXER_IO_PRO', config('settings.currencyexchange.fixer_io_pro')));
        }
        
        //...
		
		config()->set('currencyexchange.options.cache_ttl', env('CURRENCY_EXCHANGE_CACHE_TTL', config('settings.currencyexchange.cache_ttl', 86400)));
		config()->set('currencyexchange.options.cache_key_prefix', 'currencies-special-');
    }
}
