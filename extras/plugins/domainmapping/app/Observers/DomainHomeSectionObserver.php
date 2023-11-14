<?php

namespace extras\plugins\domainmapping\app\Observers;

use App\Helpers\Files\Storage\StorageDisk;
use extras\plugins\domainmapping\app\Models\DomainHomeSection;
use Illuminate\Support\Facades\Cache;

class DomainHomeSectionObserver
{
	/**
	 * Listen to the Entry updating event.
	 *
	 * @param  DomainHomeSection $homeSection
	 * @return void
	 */
	public function updating(DomainHomeSection $homeSection)
	{
		if (isset($homeSection->method) && isset($homeSection->value)) {
			// Get the original object values
			$original = $homeSection->getOriginal();
			
			// Storage Disk Init.
			$disk = StorageDisk::getDisk();
			
			if (is_array($original) && array_key_exists('value', $original)) {
				$original['value'] = jsonToArray($original['value']);
				
				// Remove old background_image from disk
				if (array_key_exists('background_image', $homeSection->value)) {
					if (
						is_array($original['value'])
						&& isset($original['value']['background_image'])
						&& !empty($original['value']['background_image'])
						&& $homeSection->value['background_image'] != $original['value']['background_image']
						&& $disk->exists($original['value']['background_image'])
					) {
						$disk->delete($original['value']['background_image']);
					}
				}
			}
		}
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param  DomainHomeSection $homeSection
	 * @return void
	 */
	public function updated(DomainHomeSection $homeSection)
	{
		//...
	}
	
    /**
     * Listen to the Entry saved event.
     *
     * @param  DomainHomeSection $homeSection
     * @return void
     */
    public function saved(DomainHomeSection $homeSection)
    {
        // Removing Entries from the Cache
        $this->clearCache($homeSection);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  DomainHomeSection $homeSection
     * @return void
     */
    public function deleted(DomainHomeSection $homeSection)
    {
        // Removing Entries from the Cache
        $this->clearCache($homeSection);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $homeSection
     */
    private function clearCache($homeSection)
    {
		Cache::flush();
    }
}
