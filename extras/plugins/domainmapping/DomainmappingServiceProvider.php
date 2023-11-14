<?php

namespace extras\plugins\domainmapping;

use App\Http\Controllers\Web\Admin\Panel\Library\PanelRoutes;
use App\Http\Controllers\Web\Public\SitemapsController;
use extras\plugins\domainmapping\app\Http\Controllers\Web\Admin\DomainController;
use extras\plugins\domainmapping\app\Http\Controllers\Web\Admin\DomainHomeSectionController;
use extras\plugins\domainmapping\app\Http\Controllers\Web\Admin\DomainMetaTagController;
use extras\plugins\domainmapping\app\Http\Controllers\Web\Admin\DomainSettingController;
use extras\plugins\domainmapping\app\Providers\AppService\ConfigTrait;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class DomainmappingServiceProvider extends ServiceProvider
{
	use ConfigTrait;
	
	private int $cacheExpiration = 86400; // Cache for 1 day (60 * 60 * 24)
	
	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		// Load plugin languages files
		$this->loadTranslationsFrom(realpath(__DIR__ . '/lang'), 'domainmapping');
		
		$this->registerMiddlewares($this->app->router);
		$this->setupRoutes($this->app->router);
		
		// Setup Configs
		$this->setupConfigs();
	}
	
	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register(): void
	{
		$this->app->bind('domainmapping', fn () => new Domainmapping());
	}
	
	/**
	 * Define the routes for the application.
	 *
	 * @param Router $router
	 */
	public function setupRoutes(Router $router): void
	{
		// Front
		Route::middleware(['web'])
			->namespace('App\Http\Controllers\Web\Public')
			->group(function ($router) {
				// XML SITEMAPS
				Route::controller(SitemapsController::class)
					->group(function ($router) {
						Route::get('sitemaps.xml', 'getSitemapIndexByCountry');
						Route::get('sitemaps/pages.xml', 'getPagesSitemapByCountry');
						Route::get('sitemaps/categories.xml', 'getCategoriesSitemapByCountry');
						Route::get('sitemaps/cities.xml', 'getCitiesSitemapByCountry');
						Route::get('sitemaps/posts.xml', 'getListingsSitemapByCountry');
					});
			});
		
		// Admin
		Route::middleware(['admin', 'banned.user'])
			->namespace('extras\plugins\domainmapping\app\Http\Controllers\Web\Admin')
			->prefix(config('larapen.admin.route', 'admin'))
			->group(function ($router) {
				$router->pattern('countryCode', '[a-zA-Z]+');
				
				Route::controller(DomainHomeSectionController::class)
					->group(function ($router) {
						Route::get('domains/{countryCode}/homepage/generate', 'generate');
						Route::get('domains/{countryCode}/homepage/reset', 'reset');
					});
				Route::controller(DomainMetaTagController::class)
					->group(function ($router) {
						Route::get('domains/{countryCode}/meta_tags/generate', 'generate');
						Route::get('domains/{countryCode}/meta_tags/reset', 'reset');
					});
				Route::controller(DomainSettingController::class)
					->group(function ($router) {
						Route::get('domains/{countryCode}/settings/generate', 'generate');
						Route::get('domains/{countryCode}/settings/reset', 'reset');
					});
				Route::get('domains/create_bulk_countries_sub_domains', [DomainController::class, 'createBulkCountriesSubDomain']);
				
				PanelRoutes::resource('domains/{countryCode}/homepage', DomainHomeSectionController::class);
				PanelRoutes::resource('domains/{countryCode}/meta_tags', DomainMetaTagController::class);
				PanelRoutes::resource('domains/{countryCode}/settings', DomainSettingController::class);
				PanelRoutes::resource('domains', DomainController::class);
			});
	}
	
	/**
	 * @param \Illuminate\Routing\Router $router
	 */
	public function registerMiddlewares(Router $router): void
	{
		Route::aliasMiddleware('admin', \App\Http\Middleware\Admin::class);
		Route::aliasMiddleware('banned.user', \App\Http\Middleware\BannedUser::class);
		Route::aliasMiddleware('domain.verification', app\Http\Middleware\DomainVerification::class);
	}
	
	/**
	 * Get Country Code from Domain
	 *
	 * @return string|null
	 */
	private function getCountryCodeFromDomain(): ?string
	{
		$countryCode = null;
		
		$host = getHost(url()->current());
		$domain = collect((array)config('domains'))->firstWhere('host', $host);
		
		if (!empty($domain) && !empty($domain['country_code'])) {
			$countryCode = $domain['country_code'];
		}
		
		return is_string($countryCode) ? $countryCode : null;
	}
}
