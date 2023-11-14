<?php

namespace extras\plugins\domainmapping\app\Observers\Traits\Setting;

use App\Helpers\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Larapen\LaravelDistance\Libraries\mysql\DistanceHelper;

trait ListTrait
{
	/**
	 * Saved
	 *
	 * @param $setting
	 */
	public function listSaved($setting)
	{
		$this->saveTheDisplayModeInCookie($setting);
	}
	
	/**
	 * Save the new Display Mode in cookie
	 *
	 * @param $setting
	 */
	public function saveTheDisplayModeInCookie($setting): void
	{
		// If the Default List Mode is changed, then clear the 'list_display_mode' from the cookies
		// NOTE: The cookie has been set from JavaScript, so we have to provide the good path (may be the good expire time)
		if (isset($setting->value['display_mode'])) {
			Cookie::forget('list_display_mode');
			
			$expire = 60 * 24 * 7; // 7 days
			Cookie::set('list_display_mode', $setting->value['display_mode'], $expire);
		}
	}
}
