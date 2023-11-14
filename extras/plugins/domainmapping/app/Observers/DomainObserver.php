<?php

namespace extras\plugins\domainmapping\app\Observers;

use extras\plugins\domainmapping\app\Models\Domain;
use extras\plugins\domainmapping\app\Models\DomainHomeSection;
use extras\plugins\domainmapping\app\Models\DomainMetaTag;
use extras\plugins\domainmapping\app\Models\DomainSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class DomainObserver
{
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param  Domain $domain
	 * @return void
	 */
	public function deleting(Domain $domain)
	{
		// Get & delete settings
		$domainSettings = DomainSetting::where('country_code', $domain->country_code)->get();
		if ($domainSettings->count() > 0) {
			foreach($domainSettings as $domainSetting) {
				$domainSetting->delete();
			}
		}
		
		// Get & delete homepage sections
		$domainHomeSections = DomainHomeSection::where('country_code', $domain->country_code)->get();
		if ($domainHomeSections->count() > 0) {
			foreach($domainHomeSections as $domainHomeSection) {
				$domainHomeSection->delete();
			}
		}
		
		// Get & delete meta tags
		$domainMetaTags = DomainMetaTag::where('country_code', $domain->country_code)->get();
		if ($domainMetaTags->count() > 0) {
			foreach($domainMetaTags as $domainMetaTag) {
				$domainMetaTag->delete();
			}
		}
	}
	
    /**
     * Listen to the Entry saved event.
     *
     * @param  Domain $domain
     * @return void
     */
    public function saved(Domain $domain)
    {
        // Remove the robots.txt file
        $this->removeRobotsTxtFile();
        
        // Removing Entries from the Cache
        $this->clearCache($domain);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  Domain $domain
     * @return void
     */
    public function deleted(Domain $domain)
    {
        // Remove the robots.txt file
        $this->removeRobotsTxtFile();
        
        // Removing Entries from the Cache
        $this->clearCache($domain);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $domain
     */
    private function clearCache($domain)
    {
        Cache::flush();
    }
    
    /**
     * Remove the robots.txt file (It will be re-generated automatically)
     */
    private function removeRobotsTxtFile()
    {
        $robotsFile = public_path('robots.txt');
        if (File::exists($robotsFile)) {
            File::delete($robotsFile);
        }
    }
}
