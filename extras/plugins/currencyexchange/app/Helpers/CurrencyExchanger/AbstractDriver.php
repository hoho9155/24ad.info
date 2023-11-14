<?php

namespace extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger;

abstract class AbstractDriver
{
	public function __construct()
	{
		//...
	}
	
	/**
	 * Get Currency Exchange info.
	 *
	 * @return array
	 */
	abstract public function get();
	
	/**
	 * Get the raw Currency Exchange info from the driver.
	 *
	 * @return mixed
	 */
	abstract public function getRaw();
	
	/**
	 * Get the default values (all null).
	 *
	 * @param $responseError
	 * @return array
	 */
	protected function getDefault($responseError = null): array
	{
		$responseError = parseHttpRequestError($responseError); // required!
		
		return [
			'driver' => config('currencyexchange.default'),
			'error'  => $responseError,
			'base'   => null,
			'rates'  => null,
		];
	}
	
	/**
	 * @param $date
	 * @return string
	 */
	protected function formatDate($date): string
	{
		if (empty($date)) {
			return date('Y-m-d H:i:s');
		}
		
		if (is_numeric($date)) {
			$date = date('Y-m-d H:i:s', $date);
		} else {
			$date = str_replace('.', '-', $date);
			$date = date('Y-m-d H:i:s', strtotime($date));
		}
		
		$isValid = (!empty($date) && !str_contains($date, '1970') && str_starts_with($date, date('Y')));
		
		return ($isValid) ? $date : date('Y-m-d H:i:s');
	}
}
