<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class DomainmappingSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['share_session'] = '1';
			
		} else {
			
			if (!array_key_exists('share_session', $value)) {
				$value['share_session'] = '1';
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
				'name'         => 'share_session',
				'label'        => trans('domainmapping::messages.share_session_label'),
				'type'         => 'checkbox_switch',
				'hint'         => trans('domainmapping::messages.share_session_hint'),
			],
		];
		
		return $fields;
	}
}
