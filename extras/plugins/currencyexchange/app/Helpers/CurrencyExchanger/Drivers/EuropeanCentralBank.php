<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class EuropeanCentralBank extends AbstractDriver
{
	public function get()
	{
		$data = $this->getRaw();
		
		if (empty($data) || empty(data_get($data, 'rates')) || is_string($data) || data_get($data, 'error')) {
			return $this->getDefault($data);
		}
		
		return [
			'driver' => config('currencyexchange.default'),
			'base'   => data_get($data, 'base'),
			'rates'  => data_get($data, 'rates'),
		];
	}
	
	/**
	 * ecb
	 * https://www.ecb.europa.eu/stats/eurofxref/
	 * https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/index.en.html
	 * Free Plan: No API key required
	 *
	 * @return array|string
	 */
	public function getRaw()
	{
		$currencyBase = config('currencyexchange.drivers.ecb.currencyBase', 'EUR');
		
		$url = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
		
		try {
			$response = Http::get($url);
			if ($response->successful()) {
				$data = $response->body();
				
				$formattedData = $this->formatDataUsingRegex($data, $currencyBase);
				if (empty($formattedData) || empty($formattedData['rates'])) {
					$formattedData = $this->formatDataUsingSimplexml($data, $currencyBase);
				}
				
				return $formattedData;
			}
		} catch (\Throwable $e) {
			$response = $e;
		}
		
		return parseHttpRequestError($response);
	}
	
	/**
	 * @param $data
	 * @param string $currencyBase
	 * @return array
	 */
	private function formatDataUsingRegex($data, string $currencyBase): array
	{
		$matches = [];
		preg_match('|<Cube time=\'([^\']+)\'>|ui', $data, $matches);
		$date = $matches[1] ?? null;
		$date = $this->formatDate($date);
		
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => $date,
			'rate' => 1,
		];
		
		$matches = [];
		preg_match_all('|<Cube currency=\'([^\']+)\' rate=\'([^\']+)\'/>|ui', $data, $matches, PREG_SET_ORDER);
		$rates = collect($matches)->mapWithKeys(function ($item, $key) use ($date) {
			$code = $item[1];
			
			$rate = $item[2];
			$rate = str_replace(' ', '', $rate);
			$rate = str_replace(',', '.', $rate);
			
			return [
				$code => [
					'code' => $code,
					'date' => $date,
					'rate' => (float)$rate,
				],
			];
		});
		
		if ($rates->isNotEmpty() && !$rates->contains('code', $currencyBase)) {
			$rates = $rates->put($currencyBase, $baseItem);
		}
		
		return [
			'base'  => $currencyBase,
			'rates' => $rates->toArray(),
		];
	}
	
	/**
	 * @param $data
	 * @param string $currencyBase
	 * @return array|string
	 */
	private function formatDataUsingSimplexml($data, string $currencyBase): array|string
	{
		if (!extension_loaded('ext-simplexml') || !function_exists('simplexml_load_string')) {
			return 'The PHP ext-simplexml extension is required to get data from European Central Bank\'s API.';
		}
		
		// The PHP ext-simplexml extension is required
		$xmlObject = simplexml_load_string(trim($data));
		
		$json = json_encode($xmlObject);
		$phpArray = json_decode($json, true);
		
		$date = null;
		$rates = collect($phpArray)->flatten(2)
			->mapWithKeys(function ($item, $key) use ($currencyBase, &$date) {
				if (isset($item['time']) && !empty($item['time'])) {
					$date = $item['time'];
				}
				
				$date = $this->formatDate($date);
				
				// Main currency
				$baseItem = [
					'code' => $currencyBase,
					'date' => $date,
					'rate' => 1,
				];
				
				$rates = collect($item)->mapWithKeys(function ($v, $k) use ($date) {
					if (
						!isset($v['@attributes'])
						|| !isset($v['@attributes']['currency'])
						|| !isset($v['@attributes']['rate'])
					) {
						return [];
					}
					
					$code = $v['@attributes']['currency'];
					
					$rate = $v['@attributes']['rate'];
					$rate = str_replace(' ', '', $rate);
					$rate = str_replace(',', '.', $rate);
					
					return [
						$code => [
							'code' => $code,
							'date' => $date,
							'rate' => (float)$rate,
						],
					];
				});
				
				if ($rates->isNotEmpty() && !$rates->contains('code', $currencyBase)) {
					$rates = $rates->put($currencyBase, $baseItem);
				}
				
				return $rates->toArray();
			})->toArray();
		
		return [
			'base'  => $currencyBase,
			'rates' => $rates,
		];
	}
}
