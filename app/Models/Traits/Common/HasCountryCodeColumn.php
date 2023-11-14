<?php
/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
 */

namespace App\Models\Traits\Common;

use App\Models\Country;

trait HasCountryCodeColumn
{
	/*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
	public function getCountryHtml(): string
	{
		$out = '';
		
		if (empty($this->country) && empty($this->country_code)) {
			return $out;
		}
		
		$countryCode = $this->country->code ?? $this->country_code;
		$countryName = $this->country->name ?? $countryCode;
		
		$iconPath = 'images/flags/16/' . strtolower($countryCode) . '.png';
		if (file_exists(public_path($iconPath))) {
			$out .= '<a href="' . dmUrl($countryCode, '/', true, true) . '" target="_blank">';
			$out .= '<img src="' . url($iconPath) . getPictureVersion() . '" data-bs-toggle="tooltip" title="' . $countryName . '">';
			$out .= '</a>';
		} else {
			$out .= $countryCode;
		}
		
		return $out;
	}
	
	/*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
	public function country()
	{
		return $this->belongsTo(Country::class, 'country_code', 'code');
	}
	
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeByCountry($builder, ?string $code = null)
	{
		$code = !empty($code) ? $code : config('country.code');
		
		return $builder->where('country_code', $code);
	}
	
	public function scopeInCountry($builder, ?string $code = null)
	{
		$code = !empty($code) ? $code : config('country.code');
		
		return $builder->where('country_code', $code);
	}
	
	// Old: Need to be removed
	public function scopeCurrentCountry($builder)
	{
		return $builder->where('country_code', config('country.code'));
	}
	
	// Old: Need to be removed
	public function scopeCountryOf($builder, $countryCode)
	{
		return $builder->where('country_code', $countryCode);
	}
	
	/*
	|--------------------------------------------------------------------------
	| ACCESSORS
	|--------------------------------------------------------------------------
	*/
	
	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
