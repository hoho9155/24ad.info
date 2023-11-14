<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class ExchangerateApi extends AbstractDriver
{
	public function get()
	{
		$data = $this->getRaw();
		
		if (
			empty($data)
			|| empty(data_get($data, 'conversion_rates'))
			|| data_get($data, 'result') != 'success'
			|| !empty(data_get($data, 'error'))
			|| is_string($data)
		) {
			return $this->getDefault($data);
		}
		
		$currencyBase = data_get($data, 'base_code');
		$date = data_get($data, 'time_last_update_unix');
		$date = $this->formatDate($date);
		
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => $date,
			'rate' => 1,
		];
		
		$rates = collect(data_get($data, 'conversion_rates'))->mapWithKeys(function ($item, $key) use ($date) {
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
	 * exchangerate_api
	 * https://www.exchangerate-api.com/
	 * Free Plan: 1500 requests / month
	 *
	 * @return array|mixed|string
	 */
	public function getRaw()
	{
		// https://v6.exchangerate-api.com/v6/YOUR-API-KEY/latest/USD
		$apiKey = config('currencyexchange.drivers.exchangerate_api.apiKey');
		$currencyBase = config('currencyexchange.drivers.exchangerate_api.currencyBase', 'USD');
		$pro = config('currencyexchange.drivers.exchangerate_api.pro');
		
		$url = 'https://v6.exchangerate-api.com/v6/' . $apiKey . '/latest/' . $currencyBase;
		
		try {
			$response = Http::get($url);
			if ($response->successful()) {
				return $response->json();
			}
		} catch (\Throwable $e) {
			$response = $e;
		}
		
		return parseHttpRequestError($response);
	}
}
