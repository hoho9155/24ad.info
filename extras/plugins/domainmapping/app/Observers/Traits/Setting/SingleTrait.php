<?php

namespace extras\plugins\domainmapping\app\Observers\Traits\Setting;

use App\Models\Post;

trait SingleTrait
{
	/**
	 * Updating
	 *
	 * @param $setting
	 * @param $original
	 */
	public function singleUpdating($setting, $original)
	{
		$this->autoReviewedExistingPostsIfApprobationIsEnabled($setting);
	}
	
	/**
	 * Auto approve all the existing listings,
	 * If the Posts Approbation feature is enabled
	 *
	 * @param $setting
	 */
	private function autoReviewedExistingPostsIfApprobationIsEnabled($setting): void
	{
		// Enable Posts Approbation by User Admin (Post Review)
		if (array_key_exists('listings_review_activation', $setting->value)) {
			// If Listing Approbation is enabled,
			// then set the reviewed field to "true" for all the existing Posts
			if ((int)$setting->value['listings_review_activation'] == 1) {
				Post::where('country_code', $setting->country_code)
					->whereNull('reviewed_at')->update(['reviewed_at' => now()]);
			}
		}
	}
}
