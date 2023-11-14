<?php

namespace extras\plugins\reviews\app\Models\Setting;

class ReviewsSetting
{
	public static function getValues($value, $disk)
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
				'name'              => 'guests_comments',
				'label'             => trans('reviews::messages.guests_comments_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('reviews::messages.guests_comments_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
				'plugin'            => 'reviews',
			],
		];
		
		return $fields;
	}
}
