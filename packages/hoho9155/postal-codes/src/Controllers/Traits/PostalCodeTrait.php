<?php

namespace Hoho9155\PostalCodes\Controllers\Traits;

use GuzzleHttp\Client;

trait PostalCodeTrait
{

    public function getPostalCode($countryCode, $cityName)
    {
        $array = explode(',', $cityName);
        if (count($array) == 0) return null;

        $cities = $this->fetchRecords($countryCode, $array[0]);
        if (count($cities) == 0) return null;

        return $cities[0];
    }

    public function getCityNamesWithPostalCode($countryCode, $query)
    {
        $array = explode(',', $query);
        if (count($array) == 0) return null;
        $query = $array[0];

        $cities = $this->fetchRecords($countryCode, $query);

        $citiesArr = [];
        // Get & formats cities
		foreach ($cities as $city) {			
			$citiesArr[] = [
				'data'  => ':' . data_get($city, 'postalCode'),
				'value' => $this->getFullName($city)
			];
		}

        return $citiesArr;
    }

    public function getFullName($city) 
    {
        $postalcode = data_get($city, 'postalCode');
        $cityName = data_get($city, 'placeName');
        $admin2Name = data_get($city, 'adminName2');
        $admin1Name = data_get($city, 'adminName1');
        
        $fullCityName = !empty($admin2Name)
            ? $cityName . ', ' . $admin2Name
            : (!empty($admin1Name) ? $cityName . ', ' . $admin1Name : $cityName);
        
        return $postalcode . ', ' . $fullCityName;
    }

    public function fetchRecords($countryCode, $query)
    {
        // $client = new Client([
        //     'base_uri' => 'https://maps.googleapis.com'
        // ]);
        // $url = '/maps/api/geocode/json';
        // $response = $client->request('GET', $url, [
        //     'query' => [
        //         'address'       => $query,
        //         'key'           => config('services.googlemaps.key')
        //     ]
        // ]);
        // $body = $response->getBody();
        // $bodyJS = json_decode($body);
        // $records = $bodyJS->results;
        // return $records;

        $cacheId = $countryCode . 'fetch_records.' . $query;
        $cacheExpiration = (int)config('settings.optimization.cache_expiration', 86400) * 5;
        $records = cache()->remember($cacheId, $cacheExpiration, function () use ($countryCode, $query) {
            $client = new Client([
                'base_uri' => 'http://api.geonames.org'
            ]);
            $url = '/postalCodeSearchJSON';
            $response = $client->request('GET', $url, [
                'query' => [
                    'placename_startsWith'     => $query,
                    'username'      => 'hoho',
                    // 'isReduced'     => 'true',
                    'country'       => $countryCode,
                    'maxRows'       => '25'
                ]
            ]);
            $body = $response->getBody();
            $bodyJS = json_decode($body);
            $records = $bodyJS->postalCodes;

            return $records;
        });

        return $records;
    }
}
