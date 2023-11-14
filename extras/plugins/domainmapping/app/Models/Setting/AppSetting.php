<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

use App\Helpers\Files\Upload;

class AppSetting
{
	public static function passedValidation($request)
	{
		$params = [
			[
				'attribute' => 'logo',
				'destPath'  => 'app/logo',
				'width'     => (int)config('settings.upload.img_resize_logo_width', 454),
				'height'    => (int)config('settings.upload.img_resize_logo_height', 80),
				'ratio'     => config('settings.upload.img_resize_logo_ratio', '1'),
				'upsize'    => config('settings.upload.img_resize_logo_upsize', '1'),
				'filename'  => 'logo-',
			],
			[
				'attribute' => 'favicon',
				'destPath'  => 'app/ico',
				'width'     => (int)config('larapen.core.picture.otherTypes.favicon.width', 32),
				'height'    => (int)config('larapen.core.picture.otherTypes.favicon.height', 32),
				'ratio'     => config('larapen.core.picture.otherTypes.favicon.ratio', '1'),
				'upsize'    => config('larapen.core.picture.otherTypes.favicon.upsize', '0'),
				'filename'  => 'ico-',
			],
			[
				'attribute' => 'logo_dark',
				'destPath'  => 'app/backend',
				'width'     => (int)config('larapen.core.picture.otherTypes.adminLogo.width', 300),
				'height'    => (int)config('larapen.core.picture.otherTypes.adminLogo.height', 40),
				'ratio'     => config('larapen.core.picture.otherTypes.adminLogo.ratio', '1'),
				'upsize'    => config('larapen.core.picture.otherTypes.adminLogo.upsize', '0'),
				'filename'  => 'logo-dark-',
			],
			[
				'attribute' => 'logo_light',
				'destPath'  => 'app/backend',
				'width'     => (int)config('larapen.core.picture.otherTypes.adminLogo.width', 300),
				'height'    => (int)config('larapen.core.picture.otherTypes.adminLogo.height', 40),
				'ratio'     => config('larapen.core.picture.otherTypes.adminLogo.ratio', '1'),
				'upsize'    => config('larapen.core.picture.otherTypes.adminLogo.upsize', '0'),
				'filename'  => 'logo-light-',
			],
		];
		
		foreach ($params as $param) {
			$file = $request->hasFile($param['attribute'])
				? $request->file($param['attribute'])
				: $request->input($param['attribute']);
			
			$request->request->set($param['attribute'], Upload::image($param['destPath'], $file, $param));
		}
		
		return $request;
	}
	
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['name'] = config('app.name');
			$value['logo'] = config('larapen.core.logo');
			$value['favicon'] = config('larapen.core.favicon');
			$value['date_format'] = config('larapen.core.dateFormat.default');
			$value['datetime_format'] = config('larapen.core.datetimeFormat.default');
			
		} else {
			
			foreach ($value as $key => $item) {
				if ($key == 'logo') {
					$value['logo'] = str_replace('uploads/', '', $value['logo']);
					if (empty($value['logo']) || !$disk->exists($value['logo'])) {
						$value[$key] = config('larapen.core.logo');
					}
				}
				
				if ($key == 'favicon') {
					if (empty($value['favicon']) || !$disk->exists($value['favicon'])) {
						$value[$key] = config('larapen.core.favicon');
					}
				}
			}
			if (!array_key_exists('name', $value)) {
				$value['name'] = config('app.name');
			}
			if (!array_key_exists('logo', $value)) {
				$value['logo'] = config('larapen.core.logo');
			}
			if (!array_key_exists('favicon', $value)) {
				$value['favicon'] = config('larapen.core.favicon');
			}
			if (!array_key_exists('date_format', $value)) {
				$value['date_format'] = config('larapen.core.dateFormat.default');
			}
			if (!array_key_exists('datetime_format', $value)) {
				$value['datetime_format'] = config('larapen.core.datetimeFormat.default');
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
				'value' => trans('admin.app_html_brand_info'),
			],
			[
				'name'              => 'name',
				'label'             => trans('admin.App Name'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'slogan',
				'label'             => trans('admin.App Slogan'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'logo',
				'label'             => trans('admin.App Logo'),
				'type'              => 'image',
				'upload'            => 'true',
				'disk'              => 'public',
				'default'           => config('larapen.core.logo'),
				'wrapperAttributes' =>
					[
						'class' => 'col-md-6',
					],
			],
			[
				'name'              => 'favicon',
				'label'             => trans('admin.Favicon'),
				'type'              => 'image',
				'upload'            => 'true',
				'disk'              => 'public',
				'default'           => config('larapen.core.favicon'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_clear_1',
				'type'  => 'custom_html',
				'value' => '<div style="clear: both;"></div>',
			],
			[
				'name'              => 'email',
				'label'             => trans('admin.Email'),
				'type'              => 'email',
				'hint'              => trans('admin.The email address that all emails from the contact form will go to'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'phone_number',
				'label'             => trans('admin.Phone number'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'dates_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.dates_title'),
			],
			[
				'name'              => 'date_format',
				'label'             => trans('admin.date_format_label'),
				'type'              => 'text',
				'default'           => config('larapen.core.dateFormat.default'),
				'hint'              => trans('admin.date_format_hint') . '<br>' . trans('admin.admin_date_format_info'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'datetime_format',
				'label'             => trans('admin.datetime_format_label'),
				'type'              => 'text',
				'default'           => config('larapen.core.datetimeFormat.default'),
				'hint'              => trans('admin.date_format_hint') . '<br>' . trans('admin.admin_date_format_info'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
		];
		
		return $fields;
	}
}
