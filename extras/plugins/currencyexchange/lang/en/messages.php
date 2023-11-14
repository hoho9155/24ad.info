<?php

return [
    'Currency Exchange' => 'Currency Exchange',
    'Currencies' => 'Currencies',
    'Currency' => 'Currency',
    'Optional' => 'Optional',
    'Enable the Currency Exchange Option' => 'Enable the Currency Exchange Option',
    'Enable/Disable the Currency Exchange Option.' => 'Enable/Disable the Currency Exchange Option.',
    'currencies_codes_list_hint' => 'Enter the <a href=":url">currencies</a> codes (ISO 4217) separated by comma.',
    'currencies_codes_list_menu_hint' => '<strong>Enter the <a href=":url">currencies</a> codes (ISO 4217) in which you would like your users to do conversions (separated by comma).</strong>',
    'currencies_codes_list_menu_hint_note' => '<strong>NOTE:</strong> You can also set the currencies list per country by using the <strong>Currencies</strong> field in the add/edit form in Admin panel &rarr; Settings &rarr; International &rarr; Countries.',
    'currencies_codes_list_menu_per_country_hint' => 'Enter the <a href=":url">currencies</a> codes (ISO 4217) in which you would like your users to do conversions for this country (separated by comma).',
    'Use the codes below' => 'Use the codes below:',
    'eg_currencies_field' => 'eg. USD,EUR,CHF',
	
    'service_title' => '<h3><strong>Service</strong></h3>',
	'service_label' => 'Service',
    'service_hint' => 'This feature retrieves currency exchange rates from the services below and optionally cache the results.',
	
	'validate_service_label' => 'Validate the Service\'s response',
	'validate_service_hint' => 'Validate the Service\'s response. This validation includes (if available) the validation of its parameters.',
	
    'fixer_io_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>Get a Fixer\'s <code>Access Key</code> <a href="https://fixer.io/" target="_blank">here</a>.</p>
</div>
</div>',
    'currencylayer_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>Get a Currency Layer\'s <code>Access Key</code> <a href="https://currencylayer.com/" target="_blank">here</a>.</p>
</div>
</div>',
    'openexchangerates_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>Get an Open Exchange Rates\'s <code>App ID</code> <a href="https://openexchangerates.org/" target="_blank">here</a>.</p>
</div>
</div>',
    'exchangerate_api_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>Get an Exchange Rate API\'s <code>API Key</code> <a href="https://www.exchangerate-api.com/" target="_blank">here</a>.</p>
</div>
</div>',
	
	'service_pro_label' => 'Use pro version of this service',
	'currency_base_label' => 'Currency Base',
	'currency_base_hint' => 'Optional. Select a currency code as currency source. <br>NOTE: To use free version of this service, please do not change the value of this field.',
	
    'ecb_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>European Central Bank - Note: No API key required. See the currencies supported and their daily forex reference <a href="https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml" target="_blank">here</a>.</p>
</div>
</div>',
	'cbr_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>Russian Central Bank - Note: No API key required. See the currencies supported and their daily forex reference <a href="https://www.cbr.ru/scripts/XML_daily.asp" target="_blank">here</a>.</p>
</div>
</div>',
	'tcmb_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>Central Bank of Turkey - Note: No API key required. See the currencies supported and their daily forex reference <a href="https://www.tcmb.gov.tr/kurlar/today.xml" target="_blank">here</a>.</p>
</div>
</div>',
	'nbu_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>National Bank of Ukraine - Note: No API key required. See the currencies supported and their daily forex reference <a href="https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange" target="_blank">here</a>.</p>
</div>
</div>',
	'cnb_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>Central Bank of Czech Republic - Note: No API key required. See the currencies supported and their daily forex reference <a href="https://www.cnb.cz/cs/financni-trhy/devizovy-trh/kurzy-devizoveho-trhu/kurzy-devizoveho-trhu/denni_kurz.txt" target="_blank">here</a>.</p>
</div>
</div>',
	'bnr_info' => '<div class="card bg-light-inverse rounded mb-0">
<div class="card-body">
<p>National Bank of Romania - Note: No API key required. See the currencies supported and their daily forex reference <a href="https://www.bnr.ro/nbrfxrates.xml" target="_blank">here</a>.</p>
</div>
</div>',
	
    'other_services_title' => '<h3>Other Services</h3>',
    'options_title' => '<h3><strong>Options</strong></h3>',
    'Cache TTL' => 'Cache TTL',
    'The cache ttl in seconds.' => 'The cache ttl in seconds.',
	'rate_label' => 'Exchange Rate',
	'rate_hint' => 'This field can be updated manually. Then, it\'s (strongly) recommended to increase the "Cache TTL" from the <code>Admin panel → Settings → General → Currency Exchange</code><br>NOTE: The currency base is: :currency, using the ":driver" service for automatic updates.',
	'no_exchange_rate_found' => 'No exchange rate found for the :code currency.',
	'no_exchange_rate_found_admin' => '<strong>Only admin users can see this message</strong><br>No exchange rate found for the :code currency from the ":driver" service. You can add manually a rate for this currency by editing it from the Admin panel.',
];
