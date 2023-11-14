<?php

namespace extras\plugins\domainmapping\app\Observers\Traits\Setting;

use App\Helpers\Files\Storage\StorageDisk;

trait AppTrait
{
	/**
	 * Updating
	 *
	 * @param $setting
	 * @param $original
	 */
	public function appUpdating($setting, $original)
	{
		// Storage Disk Init.
		$disk = StorageDisk::getDisk();
		
		$this->removeOldLogoFile($setting, $original, $disk);
		$this->removeOldFaviconFile($setting, $original, $disk);
	}
	
	/**
	 * Remove old logo from disk (Don't remove the default logo)
	 *
	 * @param $setting
	 * @param $original
	 * @param $disk
	 */
	private function removeOldLogoFile($setting, $original, $disk): void
	{
		if (array_key_exists('logo', $setting->value)) {
			if (
				is_array($original['value'])
				&& !empty($original['value']['logo'])
				&& $setting->value['logo'] != $original['value']['logo']
			) {
				if (
					!str_contains($original['value']['logo'], config('larapen.core.logo'))
					&& $disk->exists($original['value']['logo'])
				) {
					$disk->delete($original['value']['logo']);
				}
			}
		}
	}
	
	/**
	 * Remove old favicon from disk (Don't remove the default favicon)
	 *
	 * @param $setting
	 * @param $original
	 * @param $disk
	 */
	private function removeOldFaviconFile($setting, $original, $disk): void
	{
		if (array_key_exists('favicon', $setting->value)) {
			if (
				is_array($original['value'])
				&& isset($original['value']['favicon'])
				&& $setting->value['favicon'] != $original['value']['favicon']
			) {
				if (
					!str_contains($original['value']['favicon'], config('larapen.core.favicon'))
					&& $disk->exists($original['value']['favicon'])
				) {
					$disk->delete($original['value']['favicon']);
				}
			}
		}
	}
}
