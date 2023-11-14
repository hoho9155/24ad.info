<?php
namespace Hoho9155\PostalCodes;

use Illuminate\Support\ServiceProvider;

class PostalCodesProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'postal-codes');
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/postal-codes'),
        ], 'public');
    }
}