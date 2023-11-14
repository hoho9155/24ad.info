<?php

return [
	
	'twocheckout' => [
		/*
		 * Integration can be: redirect, authorize
		 * - redirect: https://github.com/2Checkout/2checkout-php/wiki/Charge_Redirect
		 *             https://www.2checkout.com/documentation/checkout/standard-checkout
		 *             https://www.2checkout.com/documentation/checkout/parameters/
		 *             https://www.2checkout.com/documentation/checkout/return
		 *
		 * - authorize: https://github.com/2Checkout/2checkout-php/wiki/Charge_Authorize
		 *              https://www.2checkout.com/documentation/payment-api/create-sale
		 */
		'integration'    => env('TWOCHECKOUT_INTEGRATION', 'authorize'),
		'mode'           => env('TWOCHECKOUT_MODE', '2CO'),
		'publishableKey' => env('TWOCHECKOUT_PUBLISHABLE_KEY', ''),
		'privateKey'     => env('TWOCHECKOUT_PRIVATE_KEY', ''),
		'sellerId'       => env('TWOCHECKOUT_SELLER_ID', ''), // Merchant code
		'secretWord'     => env('TWOCHECKOUT_SECRET_WORD', ''),
		'demo'           => env('TWOCHECKOUT_DEMO', false),
	],

];
