<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class Currencylayer extends AbstractDriver
{
	public function get()
	{
		$data = $this->getRaw();
		
		if (
			empty($data)
			|| empty(data_get($data, 'quotes'))
			|| !data_get($data, 'success')
			|| !empty(data_get($data, 'error'))
			|| is_string($data)
		) {
			return $this->getDefault($data);
		}
		
		$currencyBase = data_get($data, 'source');
		$date = data_get($data, 'timestamp');
		$date = $this->formatDate($date);
		
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => $date,
			'rate' => 1,
		];
		
		$rates = collect(data_get($data, 'quotes'))
			->mapWithKeys(function ($item, $key) use ($currencyBase, $date) {
				$newKey = preg_replace('|^' . $currencyBase . '|', '', $key);
				
				return [
					$newKey => [
						'code' => $newKey,
						'date' => $date,
						'rate' => $item,
					],
				];
			});
		
		if ($rates->isNotEmpty() && !$rates->contains('code', $currencyBase)) {
			$rates = $rates->put($currencyBase, $baseItem);
		}
		
		return [
			'driver' => config('currencyexchange.default'),
			'base'   => $currencyBase,
			'rates'  => $rates->toArray(),
		];
	}
	
	/**
	 * currencylayer
	 * https://currencylayer.com/
	 * Free Plan: 250 requests / month
	 *
	 * @return array|mixed|string
	 */
	public function getRaw()
	{
		// https://api.currencylayer.com/live?access_key=YOUR_ACCESS_KEY
		$accessKey = config('currencyexchange.drivers.currencylayer.accessKey');
		$pro = config('currencyexchange.drivers.currencylayer.pro');
		
		$protocol = $pro ? 'https' : 'http';
		$url = $protocol . '://api.currencylayer.com/live';
		$query = [
			'access_key' => $accessKey,
		];
		
		try {
			$response = Http::get($url, $query);
			if ($response->successful()) {
				return $response->json();
			}
		} catch (\Throwable $e) {
			$response = $e;
		}
		
		return parseHttpRequestError($response);
	}
}
