<?php

namespace extras\plugins\currencyexchange\app\Http\Middleware;

use App\Models\Currency;
use App\Models\Permission;
use Closure;
use extras\plugins\currencyexchange\app\Helpers\CurrencyConverter;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

class CurrencyExchange
{
	/**
	 * Get the Currency Exchange Rate between the country default currency and the selected currency
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$currencyCode = config('country.currency');
		
		$selectedCurrency = config('currency');
		$selectedCurrency['rate'] = 1;
		
		if (!isFromApi()) {
			if (session()->has('curr')) {
				$currencyCode = session('curr');
			}
		}
		
		if (request()->has('curr')) {
			$currencyCode = request()->query('curr');
			if (!isFromApi()) {
				session()->put('curr', $currencyCode);
			}
		}
		
		if (
			!config('settings.currencyexchange.activation')
			|| empty($currencyCode)
		) {
			config()->set('selectedCurrency', $selectedCurrency);
			
			return $next($request);
		}
		
		$currency = Currency::find($currencyCode);
		if (empty($currency)) {
			config()->set('selectedCurrency', $selectedCurrency);
			
			return $next($request);
		}
		
		if ($currency->code == config('country.currency')) {
			config()->set('selectedCurrency', $selectedCurrency);
			
			return $next($request);
		}
		
		try {
			$currencyTo = $currency->toArray();
			$rate = CurrencyConverter::getRate($selectedCurrency, $currencyTo);
			
			// If $rate = null, then don't apply the conversion (ie skip the current currency)
			if (is_null($rate)) {
				$message = trans('currencyexchange::messages.no_exchange_rate_found', ['code' => $currency->code]);
				if (
					auth()->check()
					&& auth()->user()->can(Permission::getStaffPermissions())
				) {
					$driverName = config(
						'currencyexchange.drivers.' . config('currencyexchange.default') . '.label',
						config('currencyexchange.default')
					);
					$message = trans('currencyexchange::messages.no_exchange_rate_found_admin', [
						'code'   => $currency->code,
						'driver' => $driverName,
					]);
				}
				
				if (!isFromApi()) {
					if (isAdminPanel()) {
						Alert::warning($message)->flash();
					} else {
						flash($message)->warning();
					}
				}
				
				// Restoring null rate to 1 to prevent zero (0) price issue
				$selectedCurrency['rate'] = 1;
				config()->set('selectedCurrency', $selectedCurrency);
				
				return $next($request);
			}
			
			// Update the selected currency data (after API call is done)
			$selectedCurrency = array_merge($selectedCurrency, $currencyTo);
			$selectedCurrency['rate'] = $rate;
			
		} catch (\Throwable $e) {
			$message = $e->getMessage();
			if (!empty($message)) {
				
				if (!isFromApi()) {
					if (isAdminPanel()) {
						Alert::error($message)->flash();
					} else {
						flash($message)->error();
					}
				}
				
			}
		}
		
		config()->set('selectedCurrency', $selectedCurrency);
		
		return $next($request);
	}
}
