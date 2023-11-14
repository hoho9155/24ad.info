<?php

namespace extras\plugins\twocheckout\app\Traits;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\File;

trait InstallTrait
{
	/**
	 * @return array
	 */
	public static function getOptions(): array
	{
		$options = [];
		
		$paymentMethod = PaymentMethod::active()->where('name', 'twocheckout')->first();
		if (!empty($paymentMethod)) {
			$options[] = (object)[
				'name'     => mb_ucfirst(trans('admin.settings')),
				'url'      => admin_url('payment_methods/' . $paymentMethod->id . '/edit'),
				'btnClass' => 'btn-info',
			];
		}
		
		return $options;
	}
	
	/**
	 * @return bool
	 */
	public static function installed(): bool
	{
		$cacheExpiration = 86400; // Cache for 1 day (60 * 60 * 24)
		
		return cache()->remember('plugins.twocheckout.installed', $cacheExpiration, function () {
			$paymentMethod = PaymentMethod::active()->where('name', 'twocheckout')->first();
			if (empty($paymentMethod)) {
				return false;
			}
			
			return true;
		});
	}
	
	/**
	 * @return bool
	 */
	public static function install(): bool
	{
		// Remove old version of the plugin (if exists)
		self::removeAllVersion();
		
		// Remove the plugin entry
		self::uninstall();
		
		// Plugin data
		$data = [
			'id'                => 3,
			'name'              => 'twocheckout',
			'display_name'      => '2Checkout',
			'description'       => 'Payment with 2Checkout',
			'has_ccbox'         => 1,
			'is_compatible_api' => 0,
			'lft'               => 3,
			'rgt'               => 3,
			'depth'             => 1,
			'active'            => 1,
		];
		
		try {
			// Create plugin data
			$paymentMethod = PaymentMethod::create($data);
			if (empty($paymentMethod)) {
				return false;
			}
		} catch (\Throwable $e) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	public static function uninstall(): bool
	{
		try {
			cache()->forget('plugins.twocheckout.installed');
		} catch (\Throwable $e) {
		}
		
		$paymentMethod = PaymentMethod::where('name', 'twocheckout')
			->orWhere(function ($query) {
				$query->where('name', 'checkout')->where('display_name', '2Checkout');
			})->first();
		if (!empty($paymentMethod)) {
			$deleted = $paymentMethod->delete();
			if ($deleted > 0) {
				return true;
			}
		}
		
		return false;
	}
	
	private static function removeAllVersion(): void
	{
		try {
			// File
			$initFilePath = plugin_path('checkout', 'init.json');
			if (file_exists($initFilePath)) {
				$initFileContent = file_get_contents($initFilePath);
				if (str_contains($initFileContent, '2Checkout') || str_contains($initFileContent, '19700698')) {
					$oldVersionDir = rtrim(dirname($initFilePath), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
					File::deleteDirectory($oldVersionDir);
				}
			}
		} catch (\Throwable $e) {
			dd($e->getMessage());
		}
	}
}
