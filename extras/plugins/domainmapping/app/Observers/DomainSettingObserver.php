<?php

namespace extras\plugins\domainmapping\app\Observers;

use extras\plugins\domainmapping\app\Models\DomainSetting;
use extras\plugins\domainmapping\app\Observers\Traits\Setting\AppTrait;
use extras\plugins\domainmapping\app\Observers\Traits\Setting\ListTrait;
use extras\plugins\domainmapping\app\Observers\Traits\Setting\SingleTrait;
use extras\plugins\domainmapping\app\Observers\Traits\Setting\StyleTrait;
use Illuminate\Support\Facades\Cache;

class DomainSettingObserver
{
	use AppTrait, ListTrait, SingleTrait, StyleTrait;
	
	/**
	 * Listen to the Entry updating event.
	 *
	 * @param  DomainSetting $setting
	 * @return void
	 */
    public function updating(DomainSetting $setting)
	{
		if (isset($setting->key) && isset($setting->value)) {
			// Get the original object values
			$original = $setting->getOriginal();
			
			if (is_array($original) && array_key_exists('value', $original)) {
				$original['value'] = jsonToArray($original['value']);
				
				$settingKey = str_replace(strtolower($setting->country_code) . '_', '', $setting->key);
				$settingMethodName = str($settingKey)->camel()->ucfirst() . 'Updating';
				if (method_exists($this, $settingMethodName)) {
					$this->$settingMethodName($setting, $original);
				}
			}
		}
	}
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  DomainSetting $setting
     * @return void
     */
    public function saved(DomainSetting $setting)
    {
		$settingKey = str_replace(strtolower($setting->country_code) . '_', '', $setting->key);
		$settingMethodName = str($settingKey)->camel()->ucfirst() . 'Saved';
		if (method_exists($this, $settingMethodName)) {
			$this->$settingMethodName($setting);
		}
		
        // Removing Entries from the Cache
        $this->clearCache($setting);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  DomainSetting $setting
     * @return void
     */
    public function deleted(DomainSetting $setting)
    {
        // Removing Entries from the Cache
        $this->clearCache($setting);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $setting
     */
    private function clearCache($setting)
    {
        Cache::flush();
    }
}
