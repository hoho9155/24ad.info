<?php

namespace extras\plugins\domainmapping\app\Models\HomeSection;

class GetBottomAdvertising
{
	public static function getValues($value)
	{
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
				'name'  => 'active',
				'label' => trans('admin.Active'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.getBottomAdvertising_active_hint'),
			],
		];
		
		return $fields;
	}
}
