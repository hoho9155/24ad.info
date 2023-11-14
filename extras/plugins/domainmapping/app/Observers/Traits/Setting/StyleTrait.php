<?php

namespace extras\plugins\domainmapping\app\Observers\Traits\Setting;

use App\Helpers\Files\Storage\StorageDisk;

trait StyleTrait
{
	/**
	 * Updating
	 *
	 * @param $setting
	 * @param $original
	 */
	public function styleUpdating($setting, $original)
	{
		// Storage Disk Init.
		$disk = StorageDisk::getDisk();
		
		$this->removeOldBodyBackgroundImage($setting, $original, $disk);
	}
	
	/**
	 * Remove old body_background_image from disk
	 *
	 * @param $setting
	 * @param $original
	 * @param $disk
	 */
	private function removeOldBodyBackgroundImage($setting, $original, $disk): void
	{
		if (array_key_exists('body_background_image', $setting->value)) {
			if (
				is_array($original['value'])
				&& isset($original['value']['body_background_image'])
				&& $setting->value['body_background_image'] != $original['value']['body_background_image']
				&& $disk->exists($original['value']['body_background_image'])
			) {
				$disk->delete($original['value']['body_background_image']);
			}
		}
	}
}
