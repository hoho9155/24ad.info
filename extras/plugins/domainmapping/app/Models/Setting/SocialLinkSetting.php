<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class SocialLinkSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['facebook_page_url'] = '#';
			$value['twitter_url'] = '#';
			$value['tiktok_url'] = '#';
			$value['linkedin_url'] = '#';
			$value['pinterest_url'] = '#';
			$value['instagram_url'] = '#';
			
		} else {
			
			if (!array_key_exists('facebook_page_url', $value)) {
				$value['facebook_page_url'] = '';
			}
			if (!array_key_exists('twitter_url', $value)) {
				$value['twitter_url'] = '';
			}
			if (!array_key_exists('tiktok_url', $value)) {
				$value['tiktok_url'] = '';
			}
			if (!array_key_exists('linkedin_url', $value)) {
				$value['linkedin_url'] = '';
			}
			if (!array_key_exists('pinterest_url', $value)) {
				$value['pinterest_url'] = '';
			}
			if (!array_key_exists('instagram_url', $value)) {
				$value['instagram_url'] = '';
			}
			
		}
		
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName)
	{
		$fields = [
			[
				'name'  => 'facebook_page_url',
				'label' => trans('admin.Facebook Page URL'),
				'type'  => 'text',
			],
			[
				'name'  => 'twitter_url',
				'label' => trans('admin.Twitter URL'),
				'type'  => 'text',
			],
			[
				'name'  => 'tiktok_url',
				'label' => trans('admin.Tiktok URL'),
				'type'  => 'text',
			],
			[
				'name'  => 'linkedin_url',
				'label' => trans('admin.LinkedIn URL'),
				'type'  => 'text',
			],
			[
				'name'  => 'pinterest_url',
				'label' => trans('admin.Pinterest URL'),
				'type'  => 'text',
			],
			[
				'name'  => 'instagram_url',
				'label' => trans('admin.Instagram URL'),
				'type'  => 'text',
			],
		];
		
		return $fields;
	}
}
