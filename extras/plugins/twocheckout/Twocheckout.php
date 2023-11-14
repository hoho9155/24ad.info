<?php

namespace extras\plugins\twocheckout;

use App\Models\Post;
use App\Models\User;
use extras\plugins\twocheckout\app\Traits\InstallTrait;
use extras\plugins\twocheckout\app\Traits\AuthorizeTrait;
use extras\plugins\twocheckout\app\Traits\RedirectTrait;
use Illuminate\Http\Request;
use App\Helpers\Payment;
use App\Models\Package;

class Twocheckout extends Payment
{
	use InstallTrait, AuthorizeTrait, RedirectTrait;
	
	private static string $integrationError = 'The "integration" parameter is not set.';
	
	/**
	 * Send Payment
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param array $resData
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Exception
	 */
	public static function sendPayment(Request $request, Post|User $payable, array $resData = [])
	{
		// Set the right URLs
		parent::setRightUrls($resData);
		
		// Get the Package
		$package = Package::find($request->input('package_id'));
		
		// Don't make a payment if 'price' = 0 or null
		if (empty($package) || $package->price <= 0) {
			return redirect(parent::$uri['previousUrl'] . '?error=package')->withInput();
		}
		
		// Don't make payment if selected Package is not compatible with payable (Post|User)
		if (!parent::isPayableCompatibleWithPackage($payable, $package)) {
			return redirect(parent::$uri['previousUrl'] . '?error=packageType')->withInput();
		}
		
		// API Parameters
		// Check out the ./app/Traits/ directory
		
		// Local Parameters
		$localParams = parent::getLocalParameters($request, $payable, $package);
		
		// Try to make the Payment
		try {
			// Make the Payment
			if (config('payment.twocheckout.integration') == 'redirect') {
				// Redirect the user to 2checkout.com
				self::sendPaymentThroughRedirect($request, $payable, $package, $localParams);
				exit();
			} else if (config('payment.twocheckout.integration') == 'authorize') {
				$charge = self::sendPaymentThroughAuthorize($request, $payable, $package, $localParams);
			} else {
				return parent::paymentFailureActions($payable, self::$integrationError);
			}
			
			// Payment by Credit Card when Card info are provided from the form.
			if (
				config('payment.twocheckout.integration') == 'authorize'
				&& $charge['response']['responseCode'] == 'APPROVED'
			) {
				
				// Apply this action after successful Payment
				return self::paymentConfirmationActions($payable, $localParams);
				
			} else {
				
				$errorMessage = (!empty($charge['validationErrors']))
					? $charge['validationErrors']
					: t('payment_not_approved');
				
				// Apply this action when Payment failed
				return parent::paymentFailureActions($payable, $errorMessage);
				
			}
		} catch (\Throwable $e) {
			// dd($e);
			
			// Apply this action when API failed
			return parent::paymentApiErrorActions($payable, $e);
			
		}
	}
	
	/**
	 * NOTE: Not managed by a route.
	 * Check the method: \App\Http\Controllers\Api\Payment\MakePayment::paymentConfirmation()
	 *
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param array $params
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public static function paymentConfirmation(Post|User $payable, array $params)
	{
		// Replace patterns in URLs
		parent::$uri = parent::replacePatternsInUrls($payable, parent::$uri);
		
		// Retrieve the Payment Info
		if (
			(
				config('payment.twocheckout.integration') == 'redirect'
				&& self::paymentConfirmationThroughRedirect($params)
			)
			|| config('payment.twocheckout.integration') == 'authorize'
		) {
			// Apply actions after successful Payment
			return parent::paymentConfirmationActions($payable, $params);
		}
		
		if (session()->has('params')) {
			session()->forget('params');
		}
		
		$message = self::$integrationError;
		if (config('payment.twocheckout.integration') == 'redirect') {
			$message = 'Fail - Hash Mismatch';
		}
		
		return parent::paymentFailureActions($payable, $message);
	}
}
