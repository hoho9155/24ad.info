<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class CentralBankOfTurkey extends AbstractDriver
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
	 * tcmb
	 * https://www.tcmb.gov.tr/kurlar/today.xml
	 * Free Plan: No API key required
	 *
	 * @return array|string
	 */
	public function getRaw()
	{
		$currencyBase = config('currencyexchange.drivers.tcmb.currencyBase', 'TRY');
		
		$url = 'https://www.tcmb.gov.tr/kurlar/today.xml';
		
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
		preg_match('|<Tarih_Date Tarih="([^"]*)" Date="([^"]*)"[^>]*>|ui', $data, $matches);
		$date = $matches[1] ?? ($matches[2] ?? null);
		$date = $this->formatDate($date);
		
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => $date,
			'rate' => 1,
		];
		
		// Usage of multiline regexp in PHP. The "I miss U" technique
		$pattern = '<Currency.*CurrencyCode="([^"]*)">.*<Unit>([^<]*)</Unit>.*<ForexSelling>([^<]*)</ForexSelling>.*</Currency>';
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
			return 'The PHP ext-simplexml extension is required to get data from Central Bank of Turkey\'s API.';
		}
		
		// The PHP ext-simplexml extension is required
		$xmlObject = simplexml_load_string(trim($data));
		
		$json = json_encode($xmlObject);
		$phpArray = json_decode($json, true);
		
		$date = null;
		$rates = collect($phpArray)
			->mapWithKeys(function ($item, $key) use ($currencyBase, &$date) {
				if (!empty($item['Tarih'])) {
					$date = $item['Tarih'];
				}
				if (empty($date)) {
					if (!empty($item['Date'])) {
						$date = $item['Date'];
					}
				}
				
				// Main currency
				$baseItem = [
					'code' => $currencyBase,
					'date' => $date,
					'rate' => 1,
				];
				
				$rates = collect($item)->mapWithKeys(function ($v, $k) use ($date) {
					if (
						!isset($v['@attributes'])
						|| !isset($v['@attributes']['CurrencyCode'])
						|| !isset($v['Unit'])
						|| !isset($v['ForexSelling'])
					) {
						return [];
					}
					
					$code = $v['@attributes']['CurrencyCode'];
					
					$date = (!empty($date)) ? date('Y-m-d H:i:s', strtotime($date)) : date('Y-m-d H:i:s');
					$date = (!empty($date) && !str_contains($date, '1970') && str_starts_with($date, date('Y'))) ? $date : date('Y-m-d H:i:s');
					
					$nominal = $v['Unit'];
					$nominal = str_replace(' ', '', $nominal);
					$nominal = str_replace(',', '.', $nominal);
					$nominal = (int)$nominal;
					
					$rate = !empty($v['ForexSelling']) ? $v['ForexSelling'] : ($v['ForexBuying'] ?? 0);
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
