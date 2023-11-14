<?php

return [
	
	/*
	|--------------------------------------------------------------------------
	| GeoIP Driver Type
	|--------------------------------------------------------------------------
	|
	| Supported: 'currencylayer', 'exchangerate_api', 'openexchangerates', 'fixer_io',
	| 'ecb','cbr', 'tcmb', 'nbu', 'cnb', 'bnr'
	|
	*/
	'default' => env('CURRENCY_EXCHANGE_DRIVER', 'ecb'),
	
	/*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    */
	'drivers' => [
		/*
		 * Website: https://currencylayer.com/
		 */
		'currencylayer'       => [
			'label'        => 'currencylayer.com',
			'accessKey'    => env('CURRENCYLAYER_ACCESS_KEY'),
			'currencyBase' => 'USD',
			'pro'          => false,
		],
		
		/*
		 * Website: https://www.exchangerate-api.com/
		 */
		'exchangerate_api'    => [
			'label'        => 'exchangerate-api.com',
			'apiKey'       => env('EXCHANGERATE_API_KEY'),
			'currencyBase' => 'USD',
			'pro'          => false,
		],
		
		/*
		 * Website: https://exchangeratesapi.io/
		 */
		'exchangeratesapi_io' => [
			'label'        => 'exchangeratesapi.io',
			'accessKey'    => env('EXCHANGERATESAPI_IO_ACCESS_KEY'),
			'currencyBase' => 'EUR',
			'pro'          => false,
		],
		
		/*
		 * Website: https://openexchangerates.org/
		 */
		'openexchangerates'   => [
			'label'        => 'openexchangerates.org',
			'appId'        => env('OPENEXCHANGERATES_APP_ID'),
			'currencyBase' => 'USD',
			'pro'          => false,
		],
		
		/*
		 * Website: https://fixer.io/
		 */
		'fixer_io'            => [
			'label'        => 'fixer.io',
			'accessKey'    => env('FIXER_IO_ACCESS_KEY'),
			'currencyBase' => 'EUR',
			'pro'          => false,
		],
		
		/*
		 * European Central Bank
		 * Website: https://www.ecb.europa.eu/stats/eurofxref/
		 * No API key required
		 */
		'ecb'                 => [
			'label'        => 'European Central Bank',
			'currencyBase' => 'EUR',
		],
		
		/*
		 * Russian Central Bank
		 * Website: https://www.cbr.ru/scripts/XML_daily.asp
		 * No API key required
		 */
		'cbr'                 => [
			'label'        => 'Russian Central Bank',
			'currencyBase' => 'RUB',
		],
		
		/*
		 * Central Bank of Turkey
		 * Website: https://www.tcmb.gov.tr/kurlar/today.xml
		 * No API key required
		 */
		'tcmb'                => [
			'label'        => 'Central Bank of Turkey',
			'currencyBase' => 'TRY',
		],
		
		/*
		 * National Bank of Ukraine
		 * Website: https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange
		 * No API key required
		 */
		'nbu'                 => [
			'label'        => 'National Bank of Ukraine',
			'currencyBase' => 'UAH',
		],
		
		/*
		 * Central Bank of Czech Republic
		 * Website: https://www.cnb.cz/cs/financni-trhy/devizovy-trh/kurzy-devizoveho-trhu/kurzy-devizoveho-trhu/denni_kurz.txt
		 * No API key required
		 */
		'cnb'                 => [
			'label'        => 'Central Bank of Czech Republic',
			'currencyBase' => 'CZK',
		],
		
		/*
		 * National Bank of Romania
		 * Website: https://www.bnr.ro/nbrfxrates.xml
		 * No API key required
		 */
		'bnr'                 => [
			'label'        => 'National Bank of Romania',
			'currencyBase' => 'RON',
		],
	],
	
	/*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | The options to pass to Swap amongst:
    |
    | * cache_ttl: The cache ttl in seconds.
    */
	'options' => [
		'cache_ttl'        => env('CURRENCY_EXCHANGE_CACHE_TTL', 86400),
		'cache_key_prefix' => 'currencies-special-',
	],

];
