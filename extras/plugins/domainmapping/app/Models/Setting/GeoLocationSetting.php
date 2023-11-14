<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class GeoLocationSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['show_country_flag'] = '1';
			
		} else {
			
			if (!array_key_exists('show_country_flag', $value)) {
				$value['show_country_flag'] = '1';
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
				'name'              => 'show_country_flag',
				'label'             => trans('admin.show_country_flag_label'),
				'type'              => 'checkbox_switch',
				'hint'              => '<br>',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'local_currency_packages_activation',
				'label'             => trans('admin.Allow users to pay the Packages in their country currency'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.package_currency_by_country_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
		];
		
		return $fields;
	}
}
