<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class FixerIo extends AbstractDriver
{
	public function get()
	{
		$data = $this->getRaw();
		
		if (empty($data) || empty(data_get($data, 'rates')) || !data_get($data, 'success') || !empty(data_get($data, 'error')) || is_string($data)) {
			return $this->getDefault($data);
		}
		
		$currencyBase = data_get($data, 'base');
		$date = data_get($data, 'date');
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
	 * fixer_io
	 * https://fixer.io/
	 * Free Plan: 100 requests / month
	 *
	 * @return array|mixed|string
	 */
	public function getRaw()
	{
		// https://data.fixer.io/api/latest?access_key=API_KEY&base=USD
		$accessKey = config('currencyexchange.drivers.fixer_io.accessKey');
		$currencyBase = config('currencyexchange.drivers.fixer_io.currencyBase', 'EUR');
		$pro = config('currencyexchange.drivers.fixer_io.pro');
		
		$protocol = $pro ? 'https' : 'http';
		$url = $protocol . '://data.fixer.io/api/latest';
		$query = [
			'access_key' => $accessKey,
			'base'       => $currencyBase,
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
