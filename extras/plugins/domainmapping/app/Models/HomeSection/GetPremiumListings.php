<?php

namespace extras\plugins\domainmapping\app\Models\HomeSection;

class GetPremiumListings
{
	public static function getValues($value)
	{
		if (empty($value)) {
			
			$value['max_items'] = '20';
			$value['items_in_carousel'] = '1';
			$value['autoplay'] = '1';
			
		} else {
			
			if (!isset($value['max_items'])) {
				$value['max_items'] = '20';
			}
			if (!isset($value['items_in_carousel'])) {
				$value['items_in_carousel'] = '1';
			}
			if (!isset($value['autoplay'])) {
				$value['autoplay'] = '1';
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
				'name'              => 'max_items',
				'label'             => trans('admin.Max Items'),
				'type'              => 'number',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'order_by',
				'label'             => trans('admin.Order By'),
				'type'              => 'select2_from_array',
				'options'           => [
					'date'   => 'Date',
					'random' => 'Random',
				],
				'allows_null'       => false,
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'items_in_carousel',
				'label'             => trans('admin.items_in_carousel_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.items_in_carousel_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'autoplay',
				'label' => trans('admin.carousel_autoplay'),
				'type'  => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'autoplay_timeout',
				'label'             => trans('admin.carousel_autoplay_timeout'),
				'type'              => 'number',
				'attributes'        => [
					'placeholder' => 1500,
				],
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'cache_expiration',
				'label'             => trans('admin.Cache Expiration Time for this section'),
				'type'              => 'number',
				'attributes'        => [
					'placeholder' => '0',
				],
				'hint'              => trans('admin.home_cache_expiration_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_last',
				'type'  => 'custom_html',
				'value' => '<hr>',
			],
			[
				'name'  => 'hide_on_mobile',
				'label' => trans('admin.hide_on_mobile_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.hide_on_mobile_hint'),
			],
			[
				'name'  => 'active',
				'label' => trans('admin.Active'),
				'type'  => 'checkbox_switch',
			],
		];
		
		return $fields;
	}
}
