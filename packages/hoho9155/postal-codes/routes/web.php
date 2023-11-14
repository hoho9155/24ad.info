<?php

use Illuminate\Support\Facades\Route;
use Hoho9155\PostalCodes\Controllers\PostalCodeController;
use Hoho9155\PostalCodes\Controllers\CityNameTranslateController;
use Hoho9155\PostalCodes\Controllers\CategoryTranslateController;

Route::namespace('Hoho9155\PostalCodes')
    ->prefix('postal-codes')
    ->group(function ($router) {
		Route::get('search', [PostalCodeController::class, 'search']);
		Route::get('translate/city', [CityNameTranslateController::class, 'index']);
		Route::get('translate/city/translate', [CityNameTranslateController::class, 'translate']);
		Route::get('translate/city/countries', [CityNameTranslateController::class, 'countries']);
		Route::get('translate/city/cities', [CityNameTranslateController::class, 'cities']);

		Route::get('translate/category', [CategoryTranslateController::class, 'index']);
		Route::get('translate/category/categories', [CategoryTranslateController::class, 'categories']);
		Route::get('translate/category/translate', [CategoryTranslateController::class, 'translate']);
    });
