<?php

namespace extras\plugins\twocheckout\app\Traits;

use App\Helpers\Number;
use App\Models\Package;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

trait AuthorizeTrait
{
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param \App\Models\Package $package
	 * @param array $localParams
	 * @return array|false|mixed|string
	 */
	public static function sendPaymentThroughAuthorize(
		Request   $request,
		Post|User $payable,
		Package   $package,
		array     $localParams = []
	)
	{
		/*
		 * https://www.2checkout.com/documentation/payment-api/tutorial
		 */
		
		// Get the gateway token
		$token = $request->input('twocheckoutToken');
		
		if (empty($token)) {
			$errorMessage = t('token_not_generated');
			
			// Apply this action when API failed
			return parent::paymentApiErrorActions($payable, null, $errorMessage);
		}
		
		// Price
		$price = Number::toFloat(round($package->price, 2));
		
		try {
			/*
			 * Parameters
			 * https://github.com/2Checkout/2checkout-php/wiki/Charge_Authorize
			 * https://www.2checkout.com/documentation/payment-api/create-sale
			 */
			$charge = [
				'sellerId'        => config('payment.twocheckout.sellerId'),
				'merchantOrderId' => $payable->id,
				'transactionId'   => rand(11111111, 99999999),
				'token'           => $token,
				/*
				 * AFN, ALL, DZD, ARS, AUD, AZN, BSD, BDT, BBD, BZD, BMD, BOB, BWP, BRL, GBP, BND, BGN,
				 * CAD, CLP, CNY, COP, CRC, HRK, CZK, DKK, DOP, XCD, EGP, EUR, FJD, GTQ, HKD, HNL, HUF,
				 * INR, IDR, ILS, JMD, JPY, KZT, KES, LAK, MMK, LBP, LRD, MOP, MYR, MVR, MRO, MUR, MXN,
				 * MAD, NPR, TWD, NZD, NIO, NOK, PKR, PGK, PEN, PHP, PLN, QAR, RON, RUB, WST, SAR, SCR,
				 * SGD, SBD, ZAR, KRW, LKR, SEK, CHF, SYP, THB, TOP, TTD, TRY, UAH, AED, USD, VUV, VND,
				 * XOF, YER
				 * Use to specify the currency for the sale. Required.
				 */
				'currency'        => $package->currency_code,
				'total'           => $price,
				'billingAddr'     => [
					'name'        => $request->input('twocheckoutCardFirstName') . ' ' . $request->input('twocheckoutCardLastName'),
					'addrLine1'   => $request->input('twocheckoutBillingAddress1'),
					/*
					 * Required if "country" value is: CHN, JPN, RUS - Optional for all other "country" values
					 */
					'addrLine2'   => $request->input('twocheckoutBillingAddress2'),
					'city'        => $request->input('twocheckoutBillingCity'),
					/*
					 * Required if "country" value is:
					 * ARG, AUS, BGR, CAN, CHN, CYP, EGY, FRA, IND, IDN, ITA, JPN, MYS, MEX, NLD,
					 * PAN, PHL, POL, ROU, RUS, SRB, SGP, ZAF, ESP, SWE, THA, TUR, GBR, USA
					 * Optional for all other "country" values
					 */
					'state'       => $request->input('twocheckoutBillingState'),
					/*
					 * Required if "country" value is:
					 * ARG, AUS, BGR, CAN, CHN, CYP, EGY, FRA, IND, IDN, ITA, JPN, MYS, MEX, NLD,
					 * PAN, PHL, POL, ROU, RUS, SRB, SGP, ZAF, ESP, SWE, THA, TUR, GBR, USA
					 * Optional for all other "country" values
					 */
					'zipCode'     => $request->input('twocheckoutBillingZipCode'),
					'country'     => $request->input('twocheckoutBillingCountry'),
					'email'       => $payable->email ?? '',
					'phoneNumber' => $payable->phone ?? '',
					/*
					 * Optional
					 */
					'phoneExt'    => '',
				],
			];
			
			// To place a test order with 2Checkout's legacy Payment API,
			// just add the demo property to the root of your JSON payload with a value of TRUE.
			if (config('payment.twocheckout.demo')) {
				$charge['demo'] = true;
			}
			
			// Include 2Checkout PHP library
			require_once(__DIR__ . '/../Helpers/2checkout-php/lib/Twocheckout.php');
			
			// Set API key
			\Twocheckout::privateKey(config('payment.twocheckout.privateKey'));
			\Twocheckout::sellerId(config('payment.twocheckout.sellerId'));
			
			// If you want to turn off SSL verification (Please don't do this in your production environment)
			// this is set to true by default
			\Twocheckout::verifySSL($request->getPort() === 443);
			
			// All methods return an Array by default, or you can set the format to 'json' to get a JSON response.
			// \Twocheckout::format('array');
			
			// Make the Payment
			$chargeResponse = \Twocheckout_Charge::auth($charge);
			
			// Save the Transaction ID at the Provider
			$localParams['transaction_id'] = data_get($chargeResponse, 'response.transactionId');
			
			if ($chargeResponse['response']['responseCode'] == 'APPROVED') {
				// Save local parameters into session
				session()->put('params', $localParams);
				session()->save(); // If redirection to an external URL will be done using PHP header() function
			}
			
			return $chargeResponse;
		} catch (\Throwable $e) {
			abort(400, $e->getMessage());
		}
	}
}
