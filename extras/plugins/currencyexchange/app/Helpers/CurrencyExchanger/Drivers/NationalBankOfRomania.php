<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers;

use extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\AbstractDriver;
use Illuminate\Support\Facades\Http;

class NationalBankOfRomania extends AbstractDriver
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
	 * bnr
	 * https://www.bnr.ro/nbrfxrates.xml
	 * Free Plan: No API key required
	 *
	 * @return array|string
	 */
	public function getRaw()
	{
		$currencyBase = config('currencyexchange.drivers.bnr.currencyBase', 'RON');
		
		$url = 'https://www.bnr.ro/nbrfxrates.xml';
		
		try {
			$response = Http::get($url);
			if ($response->successful()) {
				$data = $response->body();
				
				return $this->formatData($data, $currencyBase);
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
	private function formatData($data, string $currencyBase): array
	{
		$matches = [];
		preg_match('|<Cube date="([^"]+)">|ui', $data, $matches);
		$date = $matches[1] ?? null;
		$date = $this->formatDate($date);
		
		$matches = [];
		preg_match('|<Cube[^>]*>(.+)</Cube>|sUi', $data, $matches);
		if (empty($matches[1])) {
			return [];
		}
		
		// Main currency
		$baseItem = [
			'code' => $currencyBase,
			'date' => $date,
			'rate' => 1,
		];
		
		$rates = [];
		$lines = explode("\n", $matches[1]);
		foreach ($lines as $line) {
			$matches = [];
			$pattern = (str_contains($line, 'multiplier'))
				? '<Rate currency="([^"]+)" multiplier="([^"]+)">([^<]+)</Rate>'
				: '<Rate currency="([^"]+)">([^<]+)</Rate>';
			preg_match('|' . $pattern . '|ui', $line, $matches);
			
			if (!isset($matches['1']) || !isset($matches['2'])) {
				continue;
			}
			
			$code = $matches[1];
			
			if (empty($matches[3])) {
				$nominal = 1;
				$rate = $matches[2];
			} else {
				$nominal = $matches[2];
				$nominal = str_replace(' ', '', $nominal);
				$nominal = str_replace(',', '.', $nominal);
				
				$rate = $matches[3];
			}
			$nominal = (int)$nominal;
			
			$rate = str_replace(' ', '', $rate);
			$rate = str_replace(',', '.', $rate);
			$rate = (float)$rate;
			
			$rates[$code] = [
				'code' => $code,
				'date' => $date,
				'rate' => ($nominal / $rate),
			];
		}
		
		if (!empty($rates) && !isset($rates[$currencyBase])) {
			$rates[$currencyBase] = $baseItem;
		}
		
		return [
			'base'  => $currencyBase,
			'rates' => $rates,
		];
	}
}
