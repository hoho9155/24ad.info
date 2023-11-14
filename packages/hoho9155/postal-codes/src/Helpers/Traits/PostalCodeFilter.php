<?php

namespace Hoho9155\PostalCodes\Helpers\Traits;

use Illuminate\Support\Facades\DB;
use Larapen\LaravelDistance\Distance;

trait PostalCodeFilter 
{
    private function applyLocationByPostalCode($postalcode): void
	{
        $lat = round(data_get($postalcode, 'lat'), 2);
        $lng = round(data_get($postalcode, 'lng'), 2);

		if (empty($lng) || empty($lat)) {
			return;
		}
				
		// OrderBy Priority for Location
		$this->orderBy[] = $this->postsTable . '.created_at DESC';
		
		if (config('settings.list.cities_extended_searches')) {

			// Use the Cities Extended Searches
			config()->set('distance.functions.default', config('settings.list.distance_calculation_formula'));
			config()->set('distance.countryCode', config('country.code'));

			$sql = Distance::select('lon', 'lat', $lng, $lat);
			if ($sql) {
				$this->posts->addSelect(DB::raw($sql));
				$this->having[] = Distance::having(self::$distance);
				$this->orderBy[] = Distance::orderBy('ASC');
			}
			
		}
	}
}