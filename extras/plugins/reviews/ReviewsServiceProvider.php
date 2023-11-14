<?php

namespace extras\plugins\reviews;

use App\Http\Controllers\Web\Admin\Panel\Library\PanelRoutes;
use extras\plugins\reviews\app\Http\Controllers\Api\ReviewController as ReviewApiController;
use extras\plugins\reviews\app\Http\Controllers\Web\Public\ReviewController as ReviewPublicController;
use extras\plugins\reviews\app\Http\Controllers\Web\Admin\ReviewController as ReviewAdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class ReviewsServiceProvider extends ServiceProvider
{
	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		// Load plugin views
		$this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'reviews');
		
		// Load plugin languages files
		$this->loadTranslationsFrom(realpath(__DIR__ . '/lang'), 'reviews');
		
		$this->registerMiddlewares($this->app->router);
		$this->setupRoutes($this->app->router);
	}
	
	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register(): void
	{
		$this->app->bind('reviews', fn () => new Reviews());
	}
	
	/**
	 * Define the routes for the application.
	 *
	 * @param Router $router
	 */
	public function setupRoutes(Router $router): void
	{
		// API
		Route::middleware(['api'])
			->namespace('extras\plugins\reviews\app\Http\Controllers\Api')
			->prefix('api/plugins')
			->group(function ($router) {
				Route::controller(ReviewApiController::class)
					->group(function ($router) {
						$router->pattern('postId', '[0-9]+');
						$router->pattern('ids', '[0-9,]+');
						Route::get('posts/{postId}/reviews', 'index')->name('reviews.index');
						Route::post('posts/{postId}/reviews', 'store')->name('reviews.store');
						Route::delete('posts/{postId}/reviews/{ids}', 'destroy')->name('reviews.destroy');
					});
			});
		
		// Front
		Route::middleware(['web'])
			->namespace('extras\plugins\reviews\app\Http\Controllers\Web\Public')
			->group(function ($router) {
				Route::controller(ReviewPublicController::class)
					->group(function ($router) {
						$router->pattern('postId', '[0-9]+');
						$router->pattern('id', '[0-9]+');
						Route::post('posts/{postId}/reviews/create', 'store');
						Route::get('posts/{postId}/reviews/{id}/delete', 'destroy');
						Route::post('posts/{postId}/reviews/delete', 'destroy');
					});
			});
		
		// Admin
		Route::middleware(['admin', 'banned.user'])
			->namespace('extras\plugins\reviews\app\Http\Controllers\Web\Admin')
			->prefix(config('larapen.admin.route', 'admin'))
			->group(function ($router) {
				PanelRoutes::resource('reviews', ReviewAdminController::class);
			});
	}
	
	public function registerMiddlewares(Router $router): void
	{
		Route::aliasMiddleware('admin', \App\Http\Middleware\Admin::class);
		Route::aliasMiddleware('banned.user', \App\Http\Middleware\BannedUser::class);
	}
}
