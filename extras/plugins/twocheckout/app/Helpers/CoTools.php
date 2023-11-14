<?php

namespace extras\plugins\twocheckout\app\Helpers;

use App\Models\Country;
use App\Models\Scopes\ActiveScope;

class CoTools
{
	protected static $cacheExpiration = 3600; // In minutes (e.g. 60 * 60 for 1h)
	
	/**
	 * Get Countries List
	 */
	public static function getCountries()
	{
		self::$cacheExpiration = (int)config('settings.optimization.cache_expiration', self::$cacheExpiration);
		
		$cacheId = 'twocheckout.iso3.countries.list';
		
		return cache()->remember($cacheId, self::$cacheExpiration, function () {
			return Country::query()
				->withoutGlobalScopes([ActiveScope::class])
				->orderBy('name')
				->get(['iso3', 'code', 'name']);
		});
	}
	
	/**
	 * @return false|string
	 */
	public static function countriesWhereAddrLine2IsRequired()
	{
		$countries = ['CHN', 'JPN', 'RUS'];
		
		return json_encode($countries);
	}
	
	/**
	 * @return false|string
	 */
	public static function countriesWhereZipCodeIsRequired()
	{
		$countries = [
			'ARG', 'AUS', 'BGR', 'CAN', 'CHN', 'CYP', 'EGY', 'FRA', 'IND', 'IDN', 'ITA', 'JPN', 'MYS', 'MEX', 'NLD',
			'PAN', 'PHL', 'POL', 'ROU', 'RUS', 'SRB', 'SGP', 'ZAF', 'ESP', 'SWE', 'THA', 'TUR', 'GBR', 'USA'
		];
		
		return json_encode($countries);
	}
}
