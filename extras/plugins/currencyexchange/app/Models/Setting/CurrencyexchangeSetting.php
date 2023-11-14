<?php

namespace extras\plugins\currencyexchange\app\Models\Setting;

use App\Models\Currency;

class CurrencyexchangeSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['activation'] = '1';
			$value['currencies'] = 'USD,EUR';
			$value['driver'] = 'ecb';
			$value['currencylayer_base'] = config('currencyexchange.drivers.currencylayer.currencyBase', 'USD');
			$value['exchangerate_api_base'] = config('currencyexchange.drivers.exchangerate_api.currencyBase', 'USD');
			$value['exchangeratesapi_io_base'] = config('currencyexchange.drivers.exchangeratesapi_io.currencyBase', 'USD');
			$value['openexchangerates_base'] = config('currencyexchange.drivers.openexchangerates.currencyBase', 'USD');
			$value['fixer_io_base'] = config('currencyexchange.drivers.fixer_io.currencyBase', 'EUR');
			$value['cache_ttl'] = '86400';
			
		} else {
			
			if (!isset($value['activation'])) {
				$value['activation'] = '1';
			}
			if (!isset($value['currencies'])) {
				$value['currencies'] = 'USD,EUR';
			}
			if (!isset($value['driver'])) {
				$value['driver'] = 'ecb';
			}
			if (!isset($value['currencylayer_base'])) {
				$value['currencylayer_base'] = config('currencyexchange.drivers.currencylayer.currencyBase', 'USD');
			}
			if (!isset($value['exchangerate_api_base'])) {
				$value['exchangerate_api_base'] = config('currencyexchange.drivers.exchangerate_api.currencyBase', 'USD');
			}
			if (!isset($value['exchangeratesapi_io_base'])) {
				$value['exchangeratesapi_io_base'] = config('currencyexchange.drivers.exchangeratesapi_io.currencyBase', 'USD');
			}
			if (!isset($value['openexchangerates_base'])) {
				$value['openexchangerates_base'] = config('currencyexchange.drivers.openexchangerates.currencyBase', 'USD');
			}
			if (!isset($value['fixer_io_base'])) {
				$value['fixer_io_base'] = config('currencyexchange.drivers.fixer_io.currencyBase', 'EUR');
			}
			if (!isset($value['cache_ttl'])) {
				$value['cache_ttl'] = '86400';
			}
			
		}
		
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName)
	{
		// Get Countries codes
		$currencies = Currency::get(['code']);
		$currenciesCodes = collect();
		if ($currencies->count() > 0) {
			$currenciesCodes = $currencies->keyBy('code');
		}
		
		$fields = [
			[
				'name'  => 'activation',
				'label' => trans('currencyexchange::messages.Enable the Currency Exchange Option'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('currencyexchange::messages.Enable/Disable the Currency Exchange Option.'),
			],
			[
				'name'       => 'currencies',
				'label'      => trans("currencyexchange::messages.Currencies"),
				'attributes' => [
					'placeholder' => trans('currencyexchange::messages.eg_currencies_field'),
				],
				'hint'       => trans('currencyexchange::messages.currencies_codes_list_menu_hint', ['url' => admin_url('currencies')])
					. '<br>' . trans('currencyexchange::messages.Use the codes below')
					. '<br>' . implode(', ', $currenciesCodes->keys()->toArray())
					. '<br>---<br>'
					. trans('currencyexchange::messages.currencies_codes_list_menu_hint_note'),
			],
			
			[
				'name'  => 'service_title',
				'type'  => 'custom_html',
				'value' => trans('currencyexchange::messages.service_title'),
			],
			[
				'name'              => 'driver',
				'label'             => trans('currencyexchange::messages.service_label'),
				'type'              => 'select2_from_array',
				'options'           => collect(config('currencyexchange.drivers'))
					->mapWithKeys(function ($item, $key) {
						return [$key => ($item['label'] ?? $key)];
					})->toArray(),
				'attributes'        => [
					'id' => 'driver',
				],
				'hint'              => trans('currencyexchange::messages.service_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'validate_driver',
				'label'             => trans('currencyexchange::messages.validate_service_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('currencyexchange::messages.validate_service_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'              => 'currencylayer_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.currencylayer_info'),
				'wrapperAttributes' => [
					'class' => 'currencylayer',
				],
			],
			[
				'name'              => 'currencylayer_base',
				'label'             => trans('currencyexchange::messages.currency_base_label'),
				'type'              => 'select2_from_array',
				'options'           => $currenciesCodes->mapWithKeys(fn($item, $key) => [$key => $key])->toArray(),
				'hint'              => trans('currencyexchange::messages.currency_base_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 currencylayer',
				],
			],
			[
				'name'              => 'currencylayer_pro',
				'label'             => trans('currencyexchange::messages.service_pro_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6 currencylayer',
				],
			],
			[
				'name'              => 'currencylayer_access_key',
				'label'             => 'Access Key',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6 currencylayer',
				],
			],
			
			[
				'name'              => 'exchangerate_api_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.exchangerate_api_info'),
				'wrapperAttributes' => [
					'class' => 'exchangerate_api',
				],
			],
			[
				'name'              => 'exchangerate_api_base',
				'label'             => trans('currencyexchange::messages.currency_base_label'),
				'type'              => 'select2_from_array',
				'options'           => $currenciesCodes->mapWithKeys(fn($item, $key) => [$key => $key])->toArray(),
				'hint'              => trans('currencyexchange::messages.currency_base_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 exchangerate_api',
				],
			],
			[
				'name'              => 'exchangerate_api_api_key',
				'label'             => 'API Key',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6 exchangerate_api',
				],
			],
			
			[
				'name'              => 'exchangeratesapi_io_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.exchangeratesapi_io_info'),
				'wrapperAttributes' => [
					'class' => 'exchangeratesapi_io',
				],
			],
			[
				'name'              => 'exchangeratesapi_io_base',
				'label'             => trans('currencyexchange::messages.currency_base_label'),
				'type'              => 'select2_from_array',
				'options'           => $currenciesCodes->mapWithKeys(fn($item, $key) => [$key => $key])->toArray(),
				'hint'              => trans('currencyexchange::messages.currency_base_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 exchangeratesapi_io',
				],
			],
			[
				'name'              => 'exchangeratesapi_io_pro',
				'label'             => trans('currencyexchange::messages.service_pro_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6 exchangeratesapi_io',
				],
			],
			[
				'name'              => 'exchangeratesapi_io_access_key',
				'label'             => 'Access Key',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6 exchangeratesapi_io',
				],
			],
			
			[
				'name'              => 'openexchangerates_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.openexchangerates_info'),
				'wrapperAttributes' => [
					'class' => 'openexchangerates',
				],
			],
			[
				'name'              => 'openexchangerates_base',
				'label'             => trans('currencyexchange::messages.currency_base_label'),
				'type'              => 'select2_from_array',
				'options'           => $currenciesCodes->mapWithKeys(fn($item, $key) => [$key => $key])->toArray(),
				'hint'              => trans('currencyexchange::messages.currency_base_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 openexchangerates',
				],
			],
			[
				'name'              => 'openexchangerates_app_id',
				'label'             => 'App ID',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6 openexchangerates',
				],
			],
			
			[
				'name'              => 'fixer_io_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.fixer_io_info'),
				'wrapperAttributes' => [
					'class' => 'fixer_io',
				],
			],
			[
				'name'              => 'fixer_io_base',
				'label'             => trans('currencyexchange::messages.currency_base_label'),
				'type'              => 'select2_from_array',
				'options'           => $currenciesCodes->mapWithKeys(fn($item, $key) => [$key => $key])->toArray(),
				'hint'              => trans('currencyexchange::messages.currency_base_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 fixer_io',
				],
			],
			[
				'name'              => 'fixer_io_pro',
				'label'             => trans('currencyexchange::messages.service_pro_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6 fixer_io',
				],
			],
			[
				'name'              => 'fixer_io_access_key',
				'label'             => 'Access Key',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6 fixer_io',
				],
			],
			
			[
				'name'              => 'ecb_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.ecb_info'),
				'wrapperAttributes' => [
					'class' => 'ecb',
				],
			],
			[
				'name'              => 'cbr_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.cbr_info'),
				'wrapperAttributes' => [
					'class' => 'cbr',
				],
			],
			[
				'name'              => 'tcmb_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.tcmb_info'),
				'wrapperAttributes' => [
					'class' => 'tcmb',
				],
			],
			[
				'name'              => 'nbu_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.nbu_info'),
				'wrapperAttributes' => [
					'class' => 'nbu',
				],
			],
			[
				'name'              => 'cnb_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.cnb_info'),
				'wrapperAttributes' => [
					'class' => 'cnb',
				],
			],
			[
				'name'              => 'bnr_info',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.bnr_info'),
				'wrapperAttributes' => [
					'class' => 'bnr',
				],
			],
			
			[
				'name'  => 'options_title',
				'type'  => 'custom_html',
				'value' => trans('currencyexchange::messages.options_title'),
			],
			[
				'name'              => 'cache_ttl',
				'label'             => trans('currencyexchange::messages.Cache TTL'),
				'type'              => 'number',
				'attributes'        => [
					'min'  => 1,
					'step' => '1',
				],
				'hint'              => trans('currencyexchange::messages.The cache ttl in seconds.'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'  => 'javascript',
				'type'  => 'custom_html',
				'value' => '<script>
docReady(function(e) {
	let driverEl = document.querySelector("#driver");
	getDriverFields(driverEl);
	try {
		$("#driver").on("change", function (event) {
			getDriverFields(this);
		});
	} catch (error) {
		console.log(error);
	}
});

function getDriverFields(driverEl) {
	let driverElValue = driverEl.value;
	
	hideEl(document.querySelectorAll(".currencylayer, .exchangerate_api, .exchangeratesapi_io, .openexchangerates, .fixer_io, .ecb, .cbr, .tcmb, .nbu, .cnb, .bnr"));
	
	if (driverElValue === "currencylayer") {
		showEl(document.querySelectorAll(".currencylayer"));
	}
	if (driverElValue === "exchangerate_api") {
		showEl(document.querySelectorAll(".exchangerate_api"));
	}
	if (driverElValue === "exchangeratesapi_io") {
		showEl(document.querySelectorAll(".exchangeratesapi_io"));
	}
	if (driverElValue === "openexchangerates") {
		showEl(document.querySelectorAll(".openexchangerates"));
	}
	if (driverElValue === "fixer_io") {
		showEl(document.querySelectorAll(".fixer_io"));
	}
	if (driverElValue === "ecb") {
		showEl(document.querySelectorAll(".ecb"));
	}
	if (driverElValue === "cbr") {
		showEl(document.querySelectorAll(".cbr"));
	}
	if (driverElValue === "tcmb") {
		showEl(document.querySelectorAll(".tcmb"));
	}
	if (driverElValue === "nbu") {
		showEl(document.querySelectorAll(".nbu"));
	}
	if (driverElValue === "cnb") {
		showEl(document.querySelectorAll(".cnb"));
	}
	if (driverElValue === "bnr") {
		showEl(document.querySelectorAll(".bnr"));
	}
}
</script>',
			],
		];
		
		return $fields;
	}
}
