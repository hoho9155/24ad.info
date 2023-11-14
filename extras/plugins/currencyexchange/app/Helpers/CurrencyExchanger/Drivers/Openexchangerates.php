<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class Openexchangerates extends AbstractDriver
{
	public function get()
	{
		$data = $this->getRaw();
		
		if (empty($data) || empty(data_get($data, 'rates')) || !empty(data_get($data, 'error')) || is_string($data)) {
			return $this->getDefault($data);
		}
		
		$currencyBase = data_get($data, 'base');
		$date = data_get($data, 'timestamp');
		$date = $this->formatDate($date);
		
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => $date,
			'rate' => 1,
		];
		
		$rates = collect(data_get($data, 'rates'))->mapWithKeys(function ($item, $key) use ($date) {
			return [
				$key => [
					'code' => $key,
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
	 * openexchangerates
	 * https://openexchangerates.org/
	 * Free Plan: 1,000 requests / month
	 *
	 * @return array|mixed|string
	 */
	public function getRaw()
	{
		// https://openexchangerates.org/api/latest.json?app_id=YOUR_APP_ID&base=USD
		$appId = config('currencyexchange.drivers.openexchangerates.appId');
		$currencyBase = config('currencyexchange.drivers.openexchangerates.currencyBase', 'USD');
		$pro = config('currencyexchange.drivers.openexchangerates.pro');
		
		$url = 'https://openexchangerates.org/api/latest.json';
		$query = [
			'app_id' => $appId,
			'base'   => $currencyBase,
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
