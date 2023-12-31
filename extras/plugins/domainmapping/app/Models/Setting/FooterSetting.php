<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class FooterSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['hide_payment_plugins_logos'] = '1';
			
		} else {
			
			if (!array_key_exists('hide_payment_plugins_logos', $value)) {
				$value['hide_payment_plugins_logos'] = '1';
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
				'name'  => 'hide_links',
				'label' => trans('admin.Hide Links'),
				'type'  => 'checkbox_switch',
			],
			[
				'name'  => 'hide_payment_plugins_logos',
				'label' => trans('admin.Hide Payment Plugins Logos'),
				'type'  => 'checkbox_switch',
			],
			[
				'name'  => 'hide_powered_by',
				'label' => trans('admin.Hide Powered by Info'),
				'type'  => 'checkbox_switch',
			],
			[
				'name'  => 'powered_by_info',
				'label' => trans('admin.Powered by'),
				'type'  => 'text',
			],
			[
				'name'       => 'tracking_code',
				'label'      => trans('admin.Tracking Code'),
				'type'       => 'textarea',
				'attributes' => [
					'rows' => '15',
				],
				'hint'       => trans('admin.tracking_code_hint'),
			],
		];
		
		return $fields;
	}
}
