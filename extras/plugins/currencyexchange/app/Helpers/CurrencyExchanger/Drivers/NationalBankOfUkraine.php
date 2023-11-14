<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class NationalBankOfUkraine extends AbstractDriver
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
	 * nbu
	 * https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange
	 * Free Plan: No API key required
	 *
	 * @return array|string
	 */
	public function getRaw()
	{
		$currencyBase = config('currencyexchange.drivers.nbu.currencyBase', 'UAH');
		
		$url = 'http://bank.gov.ua/NBUStatService/v1/statdirectory/exchange';
		
		try {
			$options = ['verify' => false];
			$response = Http::withOptions($options)->get($url);
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
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => date('Y-m-d H:i:s'),
			'rate' => 1,
		];
		
		// Usage of multiline regexp in PHP. The "I miss U" technique
		$pattern = '<currency[^>]*>.*<rate>([^<]+)</rate>.*<cc>([^<]+)</cc>.*<exchangedate>([^<]+)</exchangedate>.*</currency>';
		$matches = [];
		preg_match_all('|' . $pattern . '|sUi', $data, $matches, PREG_SET_ORDER);
		$rates = collect($matches)->mapWithKeys(function ($item, $key) {
			$code = $item[2];
			
			$date = $item[3];
			$date = $this->formatDate($date);
			
			$rate = $item[1];
			$rate = str_replace(' ', '', $rate);
			$rate = str_replace(',', '.', $rate);
			$rate = (float)$rate;
			
			return [
				$code => [
					'code' => $code,
					'date' => $date,
					'rate' => (1 / $rate),
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
			return 'The PHP ext-simplexml extension is required to get data from National Bank of Ukraine\'s API.';
		}
		
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => date('Y-m-d H:i:s'),
			'rate' => 1,
		];
		
		// The PHP ext-simplexml extension is required
		$xmlObject = simplexml_load_string(trim($data));
		
		$json = json_encode($xmlObject);
		$phpArray = json_decode($json, true);
		
		$rates = collect($phpArray)
			->mapWithKeys(function ($item, $key) use ($currencyBase, $baseItem) {
				$rates = collect($item)->mapWithKeys(function ($v, $k) {
					if (
						!isset($v['cc'])
						|| !isset($v['rate'])
						|| !isset($v['exchangedate'])
					) {
						return [];
					}
					
					$code = $v['cc'];
					
					$date = $v['exchangedate'];
					$date = $this->formatDate($date);
					
					$rate = $v['rate'];
					$rate = str_replace(' ', '', $rate);
					$rate = str_replace(',', '.', $rate);
					$rate = (float)$rate;
					
					return [
						$code => [
							'code' => $code,
							'date' => $date,
							'rate' => (1 / $rate),
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
