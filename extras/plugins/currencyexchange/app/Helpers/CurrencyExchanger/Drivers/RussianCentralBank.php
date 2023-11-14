<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class RussianCentralBank extends AbstractDriver
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
	 * cbr
	 * https://www.cbr.ru/scripts/XML_daily.asp
	 * Free Plan: No API key required
	 *
	 * @return array|string
	 */
	public function getRaw()
	{
		$currencyBase = config('currencyexchange.drivers.cbr.currencyBase', 'RUB');
		
		$url = 'https://www.cbr.ru/scripts/XML_daily.asp';
		
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
		preg_match('|<ValCurs Date="([^"]+)"[^>]*>|ui', $data, $matches);
		$date = $matches[1] ?? null;
		$date = $this->formatDate($date);
		
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => $date,
			'rate' => 1,
		];
		
		// Usage of multiline regexp in PHP. The "I miss U" technique
		$pattern = '<Valute[^>]*>.*<CharCode>([^<]+)</CharCode>.*<Nominal>([^<]+)</Nominal>.*<Value>([^<]+)</Value>.*</Valute>';
		$matches = [];
		preg_match_all('|' . $pattern . '|sUi', $data, $matches, PREG_SET_ORDER);
		$rates = collect($matches)->mapWithKeys(function ($item, $key) use ($date) {
			$code = $item[1];
			
			$nominal = $item[2];
			$nominal = str_replace(' ', '', $nominal);
			$nominal = str_replace(',', '.', $nominal);
			$nominal = (int)$nominal;
			
			$rate = $item[3];
			$rate = str_replace(' ', '', $rate);
			$rate = str_replace(',', '.', $rate);
			$rate = (float)$rate;
			
			return [
				$code => [
					'code' => $code,
					'date' => $date,
					'rate' => ($nominal / $rate),
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
			return 'The PHP ext-simplexml extension is required to get data from Russian Central Bank\'s API.';
		}
		
		// The PHP ext-simplexml extension is required
		$xmlObject = simplexml_load_string(trim($data));
		
		$json = json_encode($xmlObject);
		$phpArray = json_decode($json, true);
		
		$date = null;
		$rates = collect($phpArray)
			->mapWithKeys(function ($item, $key) use ($currencyBase, &$date) {
				if (isset($item['Date']) && !empty($item['Date'])) {
					$date = $item['Date'];
					$date = $this->formatDate($date);
				}
				
				// Main currency
				$baseItem = [
					'code' => $currencyBase,
					'date' => $date,
					'rate' => 1,
				];
				
				$rates = collect($item)->mapWithKeys(function ($v, $k) use ($date) {
					if (
						!isset($v['CharCode'])
						|| !isset($v['Nominal'])
						|| !isset($v['Value'])
					) {
						return [];
					}
					
					$code = $v['CharCode'];
					
					$nominal = $v['Nominal'];
					$nominal = str_replace(' ', '', $nominal);
					$nominal = str_replace(',', '.', $nominal);
					$nominal = (int)$nominal;
					
					$rate = $v['Value'];
					$rate = str_replace(' ', '', $rate);
					$rate = str_replace(',', '.', $rate);
					$rate = (float)$rate;
					
					return [
						$code => [
							'code' => $code,
							'date' => $date,
							'rate' => ($nominal / $rate),
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
