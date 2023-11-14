<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

use App\Models\Setting\Traits\WysiwygEditorsTrait;

class SingleSetting
{
	use WysiwygEditorsTrait;
	
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['publication_form_type'] = '1';
			$value['picture_mandatory'] = '1';
			$value['pictures_limit'] = '5';
			$value['guest_can_submit_listings'] = '0';
			$value['guest_can_contact_authors'] = '0';
			$value['auto_registration'] = '0';
			$value['wysiwyg_editor'] = 'tinymce';
			$value['similar_listings'] = '1';
			$value['similar_listings_limit'] = '12';
			$value['similar_listings_in_carousel'] = '1';
			
		} else {
			
			if (!array_key_exists('publication_form_type', $value)) {
				$value['publication_form_type'] = '1';
			}
			if (!array_key_exists('picture_mandatory', $value)) {
				$value['picture_mandatory'] = '1';
			}
			if (!array_key_exists('pictures_limit', $value)) {
				$value['pictures_limit'] = '5';
			}
			if (!array_key_exists('guest_can_submit_listings', $value)) {
				$value['guest_can_submit_listings'] = '0';
			}
			if (!array_key_exists('guest_can_contact_authors', $value)) {
				$value['guest_can_contact_authors'] = '0';
			}
			if (!array_key_exists('auto_registration', $value)) {
				$value['auto_registration'] = '0';
			}
			if (!array_key_exists('wysiwyg_editor', $value)) {
				$value['wysiwyg_editor'] = 'tinymce';
			}
			if (!array_key_exists('similar_listings', $value)) {
				$value['similar_listings'] = '1';
			}
			if (!array_key_exists('similar_listings_limit', $value)) {
				$value['similar_listings_limit'] = '12';
			}
			if (!array_key_exists('similar_listings_in_carousel', $value)) {
				$value['similar_listings_in_carousel'] = '1';
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
				'name'  => 'publication_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.single_html_publication'),
			],
		];
		
		$formTypeField =
			[
				'name'              => 'publication_form_type',
				'label'             => trans('admin.publication_form_type_label'),
				'type'              => 'select2_from_array',
				'options'           => [
					1 => trans('admin.publication_form_type_option_1'),
					2 => trans('admin.publication_form_type_option_2'),
				],
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			];
		
		// Add LaraClassifier extra fields
		if (config('larapen.core.itemSlug') == 'laraclassifier') {
			$formTypeField['hint'] = trans('admin.publication_form_type_hint');
		}
		
		$fields[] = $formTypeField;
		
		// Add LaraClassifier extra fields
		if (config('larapen.core.itemSlug') == 'laraclassifier') {
			$lcFields = [
				[
					'name'              => 'picture_mandatory',
					'label'             => trans('admin.picture_mandatory_label'),
					'type'              => 'checkbox_switch',
					'hint'              => trans('admin.picture_mandatory_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6',
						'style' => 'margin-top: 10px;',
					],
				],
				[
					'name'  => 'separator_clear_1',
					'type'  => 'custom_html',
					'value' => '<div style="clear: both;"></div>',
				],
				[
					'name'              => 'pictures_limit',
					'label'             => trans('admin.pictures_limit_label'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6',
					],
				],
			];
			
			$fields = array_merge($fields, $lcFields);
		}
		
		$fields = array_merge($fields, [
			[
				'name'              => 'guest_can_submit_listings',
				'label'             => trans('admin.Allow Guests to post Listings'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'listings_review_activation',
				'label'             => trans('admin.Allow listings to be reviewed by Admins'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'auto_registration_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.auto_registration_sep_value'),
			],
			[
				'name'              => 'auto_registration',
				'label'             => trans('admin.auto_registration_label'),
				'type'              => 'select2_from_array',
				'options'           => [
					0 => trans('admin.auto_registration_option_0'),
					1 => trans('admin.auto_registration_option_1'),
					2 => trans('admin.auto_registration_option_2'),
				],
				'hint'              => trans('admin.auto_registration_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'edition_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.edition_sep_value'),
			],
			[
				'name'  => 'wysiwyg_editor_title',
				'type'  => 'custom_html',
				'value' => trans('admin.wysiwyg_editor_title_value'),
			],
			[
				'name'    => 'wysiwyg_editor',
				'label'   => trans('admin.wysiwyg_editor_label'),
				'type'    => 'select2_from_array',
				'options' => self::wysiwygEditors(),
				'hint'    => trans('admin.wysiwyg_editor_hint'),
			],
			[
				'name'  => 'remove_url_title',
				'type'  => 'custom_html',
				'value' => trans('admin.remove_url_title_value'),
			],
			[
				'name'              => 'remove_url_before',
				'label'             => trans('admin.remove_element_before_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.remove_element_before_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'remove_url_after',
				'label'             => trans('admin.remove_element_after_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.remove_element_after_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'remove_email_title',
				'type'  => 'custom_html',
				'value' => trans('admin.remove_email_title_value'),
			],
			[
				'name'              => 'remove_email_before',
				'label'             => trans('admin.remove_element_before_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.remove_element_before_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'remove_email_after',
				'label'             => trans('admin.remove_element_after_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.remove_element_after_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'remove_phone_title',
				'type'  => 'custom_html',
				'value' => trans('admin.remove_phone_title_value'),
			],
			[
				'name'              => 'remove_phone_before',
				'label'             => trans('admin.remove_element_before_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.remove_element_before_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'remove_phone_after',
				'label'             => trans('admin.remove_element_after_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.remove_element_after_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'others_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.others_sep_value'),
			],
			[
				'name'              => 'guest_can_contact_authors',
				'label'             => trans('admin.guest_can_contact_authors_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.guest_can_contact_authors_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
					'style' => 'margin-top: 10px;',
				],
			],
			[
				'name'              => 'similar_listings',
				'label'             => trans('admin.similar_listings_label'),
				'type'              => 'select2_from_array',
				'options'           => [
					0 => trans('admin.similar_listings_option_0'),
					1 => trans('admin.similar_listings_option_1'),
					2 => trans('admin.similar_listings_option_2'),
				],
				'hint'              => trans('admin.similar_listings_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'similar_listings_limit',
				'label'             => trans('admin.similar_listings_limit_label'),
				'type'              => 'number',
				'attributes'        => [
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				],
				'hint'              => trans('admin.similar_listings_limit_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'similar_listings_in_carousel',
				'label'             => trans('admin.similar_listings_in_carousel_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.similar_listings_in_carousel_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'external_services_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.single_html_external_services'),
			],
			[
				'name'              => 'show_listing_on_googlemap',
				'label'             => trans('admin.Show Listings on Google Maps'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.show_listings_on_google_maps_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'activation_facebook_comments',
				'label'             => trans('admin.Allow Facebook Comments'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.allow_facebook_comments_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
		]);
		
		return $fields;
	}
}
