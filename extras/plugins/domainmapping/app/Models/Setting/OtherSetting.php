<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

use App\Models\Setting\Traits\WysiwygEditorsTrait;

class OtherSetting
{
	use WysiwygEditorsTrait;
	
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['cookie_consent_enabled'] = '0';
			$value['show_tips_messages'] = '1';
			
		} else {
			
			if (!array_key_exists('cookie_consent_enabled', $value)) {
				$value['cookie_consent_enabled'] = '0';
			}
			if (!array_key_exists('show_tips_messages', $value)) {
				$value['show_tips_messages'] = '1';
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
				'name'  => 'separator_1',
				'type'  => 'custom_html',
				'value' => trans('admin.other_html_alerts_boxes'),
			],
			[
				'name'              => 'cookie_consent_enabled',
				'label'             => trans('admin.Cookie Consent Enabled'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.Enable Cookie Consent Alert to comply for EU law'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'show_tips_messages',
				'label'             => trans('admin.Show Tips Notification Messages'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.show_tips_messages_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_2',
				'type'  => 'custom_html',
				'value' => trans('admin.other_html_google_maps'),
			],
			[
				'name'              => 'googlemaps_key',
				'label'             => trans('admin.Google Maps Key'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_4',
				'type'  => 'custom_html',
				'value' => trans('admin.other_html_number_format'),
			],
			[
				'name'  => 'decimals_superscript',
				'label' => trans('admin.Decimals Superscript'),
				'type'  => 'checkbox_switch',
			],
			[
				'name'  => 'separator_6',
				'type'  => 'custom_html',
				'value' => trans('admin.other_html_head_js'),
			],
			[
				'name'       => 'js_code',
				'label'      => trans('admin.JavaScript Code'),
				'type'       => 'textarea',
				'attributes' => [
					'rows' => '10',
				],
				'hint'       => trans('admin.js_code_hint'),
			],
		];
		
		return $fields;
	}
}
