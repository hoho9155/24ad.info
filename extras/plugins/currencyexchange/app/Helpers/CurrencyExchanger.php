<?php

namespace extras\plugins\currencyexchange\app\Helpers;

class CurrencyExchanger
{
	private array $abbr = [
		'ecb'  => 'EuropeanCentralBank',
		'cbr'  => 'RussianCentralBank',
		'tcmb' => 'CentralBankOfTurkey',
		'nbu'  => 'NationalBankOfUkraine',
		'cnb'  => 'CentralBankOfCzechRepublic',
		'bnr'  => 'NationalBankOfRomania',
	];
	
	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function getData()
	{
		try {
			$data = $this->getDriver()->get();
		} catch (\Exception $e) {
			$message = 'Failed to get the Currency Exchanger data';
			if (!empty($e->getMessage())) {
				$message = $message . ' - ' . $e->getMessage();
			}
			throw new \Exception($message, 0, $e);
		}
		
		return $data;
	}
	
	/**
	 * @param $driver
	 * @return mixed
	 * @throws \Exception
	 */
	public function getDriver($driver = null)
	{
		$defaultDriver = $driver ?? config('currencyexchange.default', '');
		
		$namespace = '\extras\plugins\currencyexchange\app\Helpers\CurrencyExchanger\Drivers\\';
		$driverClass = $namespace . str($defaultDriver)->camel()->ucfirst();
		
		if (!class_exists($driverClass) && isset($this->abbr[$defaultDriver])) {
			$driverClass = $namespace . $this->abbr[$defaultDriver];
		}
		
		if (!class_exists($driverClass)) {
			throw new \Exception(sprintf('Driver [%s] not supported.', $defaultDriver));
		}
		
		if (!method_exists($driverClass, 'get')) {
			throw new \Exception(sprintf('Driver [%s] not fully supported.', $defaultDriver));
		}
		
		return new $driverClass();
	}
}