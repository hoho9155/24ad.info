<?php

namespace extras\plugins\domainmapping\app\Observers;

use extras\plugins\domainmapping\app\Models\DomainMetaTag;
use Illuminate\Support\Facades\Cache;

class DomainMetaTagObserver
{
    /**
     * Listen to the Entry saved event.
     *
     * @param  DomainMetaTag $metaTag
     * @return void
     */
    public function saved(DomainMetaTag $metaTag)
    {
        // Removing Entries from the Cache
        $this->clearCache($metaTag);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  DomainMetaTag $metaTag
     * @return void
     */
    public function deleted(DomainMetaTag $metaTag)
    {
        // Removing Entries from the Cache
        $this->clearCache($metaTag);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $metaTag
     */
    private function clearCache($metaTag)
    {
        Cache::flush();
    }
}
