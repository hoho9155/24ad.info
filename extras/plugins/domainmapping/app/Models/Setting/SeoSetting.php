<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class SeoSetting
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
				'name'  => 'verification_tools_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.verification_tools_sep_value'),
			],
			[
				'name'              => 'google_site_verification',
				'label'             => trans('admin.google_site_verification_label'),
				'type'              => 'text',
				'hint'              => trans('admin.seo_site_verification_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'msvalidate',
				'label'             => trans('admin.msvalidate_label'),
				'type'              => 'text',
				'hint'              => trans('admin.seo_site_verification_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'yandex_verification',
				'label'             => trans('admin.yandex_verification_label'),
				'type'              => 'text',
				'hint'              => trans('admin.seo_site_verification_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'twitter_username',
				'label'             => trans('admin.twitter_username_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'  => 'no_index',
				'type'  => 'custom_html',
				'value' => trans('admin.no_index_title'),
			],
			[
				'name'              => 'no_index_categories',
				'label'             => trans('admin.no_index_categories_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_categories_qs',
				'label'             => trans('admin.no_index_categories_qs_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_cities',
				'label'             => trans('admin.no_index_cities_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_cities_qs',
				'label'             => trans('admin.no_index_cities_qs_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_users',
				'label'             => trans('admin.no_index_users_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_users_username',
				'label'             => trans('admin.no_index_users_username_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_tags',
				'label'             => trans('admin.no_index_tags_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_filters_orders',
				'label'             => trans('admin.no_index_filters_orders_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_no_result',
				'label'             => trans('admin.no_index_no_result_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_listing_report',
				'label'             => trans('admin.no_index_listing_report_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'no_index_all',
				'label'             => trans('admin.no_index_all_label'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-12',
				],
			],
		];
		
		return $fields;
	}
}
