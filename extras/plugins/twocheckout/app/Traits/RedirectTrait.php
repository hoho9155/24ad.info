<?php

namespace extras\plugins\twocheckout\app\Traits;

use App\Helpers\Number;
use App\Models\Package;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

trait RedirectTrait
{
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param \App\Models\Package $package
	 * @param array $localParams
	 * @return void
	 */
	public static function sendPaymentThroughRedirect(
		Request   $request,
		Post|User $payable,
		Package   $package,
		array     $localParams = []
	)
	{
		// Price
		$price = Number::toFloat(round($package->price, 2));
		
		try {
			// Include 2Checkout PHP library
			require_once(__DIR__ . '/../Helpers/2checkout-php/lib/Twocheckout.php');
			
			// Set API key
			\Twocheckout::privateKey(config('payment.twocheckout.privateKey'));
			\Twocheckout::sellerId(config('payment.twocheckout.sellerId'));
			
			// If you want to turn off SSL verification (Please don't do this in your production environment)
			// this is set to true by default
			\Twocheckout::verifySSL($request->getPort() === 443);
			
			// Save local parameters into session
			session()->put('params', $localParams);
			session()->save(); // If redirection to an external URL will be done using PHP header() function
			
			$isPromoting = ($package->type == 'promotion');
			$isSubscripting = ($package->type == 'subscription');
			
			$payableContactName = '';
			$packageDescription = $package->name;
			if ($isPromoting) {
				$payableContactName = $payable->contact_name;
				$packageDescription .= '(' . $payable->title . ')';
			}
			if ($isSubscripting) {
				$payableContactName = $payable->name;
				$packageDescription .= '(' . $payable->name . ')';
			}
			
			/*
			 * Parameters
			 * https://github.com/2Checkout/2checkout-php/wiki/Charge_Redirect
			 * https://www.2checkout.com/documentation/checkout/parameters/
			 */
			$charge = [
				'sid'                => config('payment.twocheckout.sellerId'),
				'mode'               => '2CO',
				'demo'               => config('payment.twocheckout.demo') ? 'Y' : 'N',
				'li_0_type'          => 'product',
				'li_0_name'          => $package->name,
				'li_0_description'   => $packageDescription,
				'li_0_price'         => $price,
				'li_0_quantity'      => 1,
				'li_0_tangible'      => 'N',
				'x_receipt_link_url' => parent::$uri['paymentReturnUrl'],
				
				// To pre-populate the billing information page you may pass in:
				'card_holder_name'   => $payableContactName,
				'email'              => $payable->email ?? '',
				'phone'              => $payable->phone ?? '',
			];
			
			\Twocheckout_Charge::redirect($charge);
		} catch (\Throwable $e) {
			abort(400, $e->getMessage());
		}
	}
	
	/**
	 * @param $params
	 * @return bool
	 */
	public static function paymentConfirmationThroughRedirect($params): bool
	{
		/*
		 * Validation
		 *
		 * The MD5 hash is provided to help you verify the authenticity of the passback to your approved URL.
		 * The hash is computed using the secret word on the Site Management page and is returned using the key parameter.
		 * To validate against the hash, you need to make a string that contains the information described below
		 * and pass it in as the value to your scripting languages MD5 function.
		 * The MD5 hash is created the same way for a production 2Checkout account and a Sandbox 2Checkout account.
		 *
		 *  UPPERCASE(MD5_ENCRYPTED(Secret Word + Seller ID + order_number + Sale Total))
		 *
		 * https://www.2checkout.com/documentation/checkout/return
		 */
		$hashSecretWord = config('payment.twocheckout.secretWord'); // 2Checkout Secret Word
		$hashSid = config('payment.twocheckout.sellerId'); // 2Checkout account number
		$hashTotal = data_get($params, 'package.price', 0); // Sale total to validate against
		$hashOrder = request()->query('order_number'); // 2Checkout Order Number
		$stringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));
		
		return !($stringToHash != request()->query('key'));
	}
}
