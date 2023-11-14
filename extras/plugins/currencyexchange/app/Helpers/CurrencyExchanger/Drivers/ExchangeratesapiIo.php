<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class ExchangeratesapiIo extends AbstractDriver
{
	public function get()
	{
		$data = $this->getRaw();
		
		if (
			empty($data)
			|| empty(data_get($data, 'rates'))
			|| !data_get($data, 'success')
			|| !empty(data_get($data, 'error'))
			|| is_string($data)
		) {
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
		
		$rates = collect(data_get($data, 'rates'))
			->mapWithKeys(function ($item, $key) use ($currencyBase, $date) {
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
	 * exchangeratesapi_io
	 * https://exchangeratesapi.io/
	 * Free Plan: 250 requests / month
	 *
	 * @return array|mixed|string
	 */
	public function getRaw()
	{
		// http://api.exchangeratesapi.io/v1/latest?access_key=YOUR_ACCESS_KEY
		$accessKey = config('currencyexchange.drivers.exchangeratesapi_io.accessKey');
		$currencyBase = config('currencyexchange.drivers.exchangeratesapi_io.currencyBase', 'EUR');
		$pro = config('currencyexchange.drivers.exchangeratesapi_io.pro');
		
		$protocol = $pro ? 'https' : 'http';
		$url = $protocol . '://api.exchangeratesapi.io/v1/latest';
		$query = [
			'access_key' => $accessKey,
			'base'       => $currencyBase,
			'format'     => 1,
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
