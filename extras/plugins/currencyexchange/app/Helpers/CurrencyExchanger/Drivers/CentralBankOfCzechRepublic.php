<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class CentralBankOfCzechRepublic extends AbstractDriver
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
	 * cnb
	 * https://www.cnb.cz/cs/financni-trhy/devizovy-trh/kurzy-devizoveho-trhu/kurzy-devizoveho-trhu/denni_kurz.txt
	 * Free Plan: No API key required
	 *
	 * @return array|string
	 */
	public function getRaw()
	{
		$currencyBase = config('currencyexchange.drivers.cnb.currencyBase', 'CZK');
		
		$url = 'https://www.cnb.cz/cs/financni-trhy/devizovy-trh/kurzy-devizoveho-trhu/kurzy-devizoveho-trhu/denni_kurz.txt';
		
		try {
			$response = Http::get($url);
			if ($response->successful()) {
				$data = $response->body();
				
				return $this->formatDataUsingRegex($data, $currencyBase);
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
		preg_match('/\d+[-.\/]\d+[-.\/]\d+/ui', $data, $matches);
		$date = $matches[0] ?? null;
		$date = $this->formatDate($date);
		
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => $date,
			'rate' => 1,
		];
		
		// Usage of multiline regexp in PHP. The "I miss U" technique
		$matches = [];
		preg_match_all('/.*\|.*\|(\d+)\|(\w{3})\|([\d,. ]+?)/sUi', $data, $matches, PREG_SET_ORDER);
		$rates = collect($matches)->mapWithKeys(function ($item, $key) use ($date) {
			$code = $item[2];
			
			$nominal = $item[1];
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
}
