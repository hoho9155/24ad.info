<?php

namespace Larapen\ReCaptcha;

use Larapen\ReCaptcha\app\Rules\ReCaptchaRule;
use Larapen\ReCaptcha\Service\ReCaptchaInvisible;
use Larapen\ReCaptcha\Service\ReCaptchaV2;
use Larapen\ReCaptcha\Service\ReCaptchaV3;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ReCaptchaServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	public function boot(): void
	{
		$this->addValidationRule();
		$this->registerRoutes();
		$this->publishes([__DIR__ . '/../config/recaptcha.php' => config_path('recaptcha.php')]);
	}
	
	/**
	 * Extends Validator to include a recaptcha type
	 */
	public function addValidationRule(): void
	{
		Validator::extendImplicit('recaptcha', function ($attribute, $value, $parameters, $validator) {
			$rule = new ReCaptchaRule();
			
			return $rule->passes($attribute, $value);
		}, trans('validation.recaptcha'));
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register(): void
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/recaptcha.php', 'recaptcha');
		
		$this->registerReCaptchaService();
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides(): array
	{
		return ['recaptcha'];
	}
	
	/**
	 * @return ReCaptchaServiceProvider
	 */
	protected function registerRoutes(): ReCaptchaServiceProvider
	{
		Route::get(
			config('recaptcha.validation_route', 'recaptcha/validate'),
			'Larapen\ReCaptcha\app\Http\Controllers\ReCaptchaController@validateV3'
		)->middleware('web');
		
		return $this;
	}
	
	/**
	 * Register the HTML builder instance.
	 *
	 * @return void
	 */
	protected function registerReCaptchaService(): void
	{
		$this->app->singleton('recaptcha', function ($app) {
			$recaptchaClass = '';
			
			switch (config('recaptcha.version')) {
				case 'v3' :
					$recaptchaClass = ReCaptchaV3::class;
					break;
				case 'v2' :
					$recaptchaClass = ReCaptchaV2::class;
					break;
				case 'invisible':
					$recaptchaClass = ReCaptchaInvisible::class;
					break;
			}
			
			return new $recaptchaClass(config('recaptcha.site_key'), config('recaptcha.secret_key'), config('recaptcha.lang'));
		});
	}
}
