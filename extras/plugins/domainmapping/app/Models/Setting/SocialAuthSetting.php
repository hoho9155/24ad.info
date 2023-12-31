<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class SocialAuthSetting
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
				'name'  => 'social_login_activation',
				'label' => trans('admin.social_login_activation_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.social_login_activation_hint'),
			],
			
			// facebook
			[
				'name'  => 'facebook_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.facebook_sep_value'),
			],
			[
				'name'  => 'facebook_sep_1',
				'type'  => 'custom_html',
				'value' => trans('admin.facebook_sep_1_value'),
			],
			[
				'name'              => 'facebook_client_id',
				'label'             => trans('admin.facebook_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'facebook_client_secret',
				'label'             => trans('admin.facebook_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			// linkedin
			[
				'name'  => 'linkedin_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.linkedin_sep_value'),
			],
			[
				'name'  => 'linkedin_sep_1',
				'type'  => 'custom_html',
				'value' => trans('admin.linkedin_sep_1_value'),
			],
			[
				'name'              => 'linkedin_client_id',
				'label'             => trans('admin.linkedin_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'linkedin_client_secret',
				'label'             => trans('admin.linkedin_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			// twitter (OAuth 2.0)
			[
				'name'  => 'sa_twitter_oauth_2_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.twitter_oauth_2_sep_value'),
			],
			[
				'name'  => 'sa_twitter_oauth_2_sep_1',
				'type'  => 'custom_html',
				'value' => trans('admin.twitter_oauth_2_sep_1_value'),
			],
			[
				'name'              => 'twitter_oauth_2_client_id',
				'label'             => trans('admin.twitter_oauth_2_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'twitter_oauth_2_client_secret',
				'label'             => trans('admin.twitter_oauth_2_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			// twitter (OAuth 1.0)
			[
				'name'  => 'twitter_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.twitter_sep_value'),
			],
			[
				'name'  => 'twitter_sep_1',
				'type'  => 'custom_html',
				'value' => trans('admin.twitter_sep_1_value'),
			],
			[
				'name'              => 'twitter_client_id',
				'label'             => trans('admin.twitter_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'twitter_client_secret',
				'label'             => trans('admin.twitter_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			// google
			[
				'name'  => 'google_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.google_sep_value'),
			],
			[
				'name'  => 'google_sep_1',
				'type'  => 'custom_html',
				'value' => trans('admin.google_sep_1_value'),
			],
			[
				'name'              => 'google_client_id',
				'label'             => trans('admin.google_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'google_client_secret',
				'label'             => trans('admin.google_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
		];
		
		return $fields;
	}
}
