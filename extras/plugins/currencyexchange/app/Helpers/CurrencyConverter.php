<?php

namespace extras\plugins\currencyexchange\app\Helpers;

use App\Helpers\Number;
use App\Models\Currency;

class CurrencyConverter
{
	/**
	 * @param array $currencyFrom
	 * @param array $currencyTo
	 * @return float|int|null
	 */
	public static function getRate(array $currencyFrom, array $currencyTo): float|int|null
	{
		// Check, Save & Get cache
		$cacheTtl = config('currencyexchange.options.cache_ttl');
		$cacheId = 'update.currencies.rates';
		cache()->remember($cacheId, $cacheTtl, function () use (&$currencyFrom, &$currencyTo) {
			self::updateCurrenciesRates();
			
			$tmpCurrencyFrom = Currency::find($currencyFrom['code']);
			if (!empty($tmpCurrencyFrom)) {
				$currencyFrom = $tmpCurrencyFrom->toArray();
			}
			
			$tmpCurrencyTo = Currency::find($currencyTo['code']);
			if (!empty($tmpCurrencyTo)) {
				$currencyTo = $tmpCurrencyTo->toArray();
			}
		});
		
		// Get base/source currency code
		// $currencyBaseCode = config('currencyexchange.drivers.' . config('currencyexchange.default') . '.currencyBase');
		
		$rate = null;
		
		if (
			(isset($currencyFrom['rate']) && !empty($currencyFrom['rate']))
			&& (isset($currencyTo['rate']) && !empty($currencyTo['rate']))
		) {
			$rateFrom = (float)Number::toFloat($currencyFrom['rate']);
			$rateTo = (float)Number::toFloat($currencyTo['rate']);
			
			// Get final rate
			$rate = ($rateTo / $rateFrom);
		}
		
		return $rate;
	}
	
	/**
	 * @param float|int $amount
	 * @param string $currencyFrom
	 * @param string $currencyTo
	 * @param int $precision
	 * @return float
	 */
	public static function convert(float|int $amount, string $currencyFrom, string $currencyTo, int $precision = 2): float
	{
		$rates = self::getCurrenciesRates();
		
		if (
			isset($rates[$currencyFrom])
			&& (isset($rates[$currencyFrom]['rate']) && !empty($rates[$currencyFrom]['rate']))
			&& (isset($rates[$currencyTo]['rate']) && !empty($rates[$currencyTo]['rate']))
		) {
			$rateFrom = (float)Number::toFloat($rates[$currencyFrom]['rate']);
			$rateTo = (float)Number::toFloat($rates[$currencyTo]['rate']);
			
			// Currency Conversion Formula
			$newAmount = $amount * ($rateTo / $rateFrom);
			
			return round($newAmount, $precision);
		}
		
		return $amount;
	}
	
	/**
	 * @return array
	 */
	public static function getCurrenciesRates(): array
	{
		$cacheTtl = config('currencyexchange.options.cache_ttl');
		$cacheId = 'update.currencies.rates';
		cache()->remember($cacheId, $cacheTtl, function () {
			self::updateCurrenciesRates();
		});
		
		$currencies = Currency::query()->get(['code', 'rate']);
		
		return $currencies->keyBy('code')->toArray();
	}
	
	/**
	 * @return bool
	 */
	public static function updateCurrenciesRates()
	{
		try {
			$data = (new CurrencyExchanger())->getData();
			$currencyBase = data_get($data, 'base');
			$rates = data_get($data, 'rates');
			
			$notSuccess = (
				!is_string($currencyBase)
				|| strlen($currencyBase) != 3
				|| !is_array($rates)
				|| empty($rates)
			);
			
			if ($notSuccess) {
				return false;
			}
			
			foreach ($rates as $rate) {
				$dataNotFound = (!isset($rate['code']) || !isset($rate['date']) || !isset($rate['rate']));
				
				if ($dataNotFound) {
					continue;
				}
				
				$currency = Currency::find($rate['code']);
				if (!empty($currency)) {
					$currency->rate = $rate['rate'];
					$currency->updated_at = $rate['date'];
					$currency->save();
				}
			}
		} catch (\Throwable $e) {
			return false;
		}
		
		return true;
	}
}