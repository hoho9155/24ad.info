<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class SmsSetting
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
				'name'              => 'enable_phone_as_auth_field',
				'label'             => trans('admin.enable_phone_as_auth_field_label'),
				'type'              => 'checkbox_switch',
				'attributes'        => [
					'id'       => 'phoneAsAuthField',
					'onchange' => 'enablePhoneNumberAsAuthField(this)',
				],
				'hint'              => trans('admin.enable_phone_as_auth_field_hint', [
					'phone_verification_label' => trans('admin.phone_verification_label'),
				]),
				'wrapperAttributes' => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'    => 'driver',
				'label'   => trans('admin.SMS Driver'),
				'type'    => 'select2_from_array',
				'options' => [
					'vonage'  => 'Vonage',
					'twilio' => 'Twilio',
				],
				'attributes' => [
					'id'       => 'driver',
					'onchange' => 'getDriverFields(this)',
				],
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'  => 'driver_vonage_title',
				'type'  => 'custom_html',
				'value' => trans('admin.driver_vonage_title'),
				'wrapperAttributes' => [
					'class' => 'col-md-12 vonage',
				],
			],
			[
				'name'  => 'driver_vonage_info',
				'type'  => 'custom_html',
				'value' => trans('admin.driver_vonage_info'),
				'wrapperAttributes' => [
					'class' => 'col-md-12 vonage',
				],
			],
			[
				'name'              => 'vonage_key',
				'label'             => trans('admin.Vonage Key'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6 vonage',
				],
			],
			[
				'name'              => 'vonage_secret',
				'label'             => trans('admin.Vonage Secret'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6 vonage',
				],
			],
			[
				'name'              => 'vonage_from',
				'label'             => trans('admin.Vonage From'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6 vonage',
				],
			],
			
			[
				'name'  => 'driver_twilio_title',
				'type'  => 'custom_html',
				'value' => trans('admin.driver_twilio_title'),
				'wrapperAttributes' => [
					'class' => 'col-md-12 twilio',
				],
			],
			[
				'name'  => 'driver_twilio_info',
				'type'  => 'custom_html',
				'value' => trans('admin.driver_twilio_info'),
				'wrapperAttributes' => [
					'class' => 'col-md-12 twilio',
				],
			],
			[
				'name'              => 'twilio_username',
				'label'             => trans('admin.twilio_username_label'),
				'type'              => 'text',
				'hint'              => trans('admin.twilio_username_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 twilio',
				],
			],
			[
				'name'              => 'twilio_password',
				'label'             => trans('admin.twilio_password_label'),
				'type'              => 'text',
				'hint'              => trans('admin.twilio_password_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 twilio',
				],
			],
			[
				'name'              => 'twilio_account_sid',
				'label'             => trans('admin.twilio_account_sid_label'),
				'type'              => 'text',
				'hint'              => trans('admin.twilio_account_sid_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 twilio',
				],
			],
			[
				'name'              => 'twilio_auth_token',
				'label'             => trans('admin.twilio_auth_token_label'),
				'type'              => 'text',
				'hint'              => trans('admin.twilio_auth_token_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 twilio',
				],
			],
			[
				'name'              => 'twilio_from',
				'label'             => trans('admin.twilio_from_label'),
				'type'              => 'text',
				'hint'              => trans('admin.twilio_from_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 twilio',
				],
			],
			[
				'name'              => 'twilio_alpha_sender',
				'label'             => trans('admin.twilio_alpha_sender_label'),
				'type'              => 'text',
				'hint'              => trans('admin.twilio_alpha_sender_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 twilio',
				],
			],
			[
				'name'              => 'twilio_sms_service_sid',
				'label'             => trans('admin.twilio_sms_service_sid_label'),
				'type'              => 'text',
				'hint'              => trans('admin.twilio_sms_service_sid_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 twilio',
				],
			],
			[
				'name'              => 'twilio_debug_to',
				'label'             => trans('admin.twilio_debug_to_label'),
				'type'              => 'text',
				'hint'              => trans('admin.twilio_debug_to_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 twilio',
				],
			],
			
			[
				'name'  => 'javascript',
				'type'  => 'custom_html',
				'value' => '<script>
docReady(function() {
	let driverEl = document.querySelector("#driver");
	getDriverFields(driverEl);
});

function getDriverFields(driverEl) {
	let driverElValue = driverEl.value;
	
	hideEl(document.querySelectorAll(".vonage, .twilio"));
	
	if (driverElValue === "vonage") {
		showEl(document.querySelectorAll(".vonage"));
	}
	if (driverElValue === "twilio") {
		showEl(document.querySelectorAll(".twilio"));
	}
}
</script>',
			],
			
			[
				'name'  => 'sms_other',
				'type'  => 'custom_html',
				'value' => trans('admin.sms_other_sep_value'),
			],
			[
				'name'  => 'phone_verification',
				'label' => trans('admin.phone_verification_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.sms_sending_requirements'),
			],
			[
				'name'  => 'messenger_notifications',
				'label' => trans('admin.messenger_notifications_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.messenger_notifications_hint') . '<br>' . trans('admin.sms_sending_requirements'),
			],
			
			[
				'name'              => 'default_auth_field_sep',
				'type'              => 'custom_html',
				'value'             => '<hr style="border: 1px dashed #EFEFEF;" class="my-3">',
				'wrapperAttributes' => [
					'class' => 'col-12 auth-field-el',
				],
			],
			[
				'name'              => 'default_auth_field',
				'label'             => trans('admin.default_auth_field_label'),
				'type'              => 'select_from_array',
				'options'           => [
					'email' => t('email_address'),
					'phone' => t('phone_number'),
				],
				'default' => 'email',
				'attributes'        => [
					'id' => 'defaultAuthField',
				],
				'hint'              => trans('admin.default_auth_field_hint', [
					'enable_phone_as_auth_field_label' => trans('admin.enable_phone_as_auth_field_label'),
					'email'                            => t('email_address'),
					'phone'                            => t('phone_number'),
				]),
				'wrapperAttributes' => [
					'class' => 'col-md-6 auth-field-el',
				],
			],
			
			[
				'name'  => 'javascript_auth_field',
				'type'  => 'custom_html',
				'value' => '<script>
docReady(function() {
	let phoneAsAuthFieldEl = document.querySelector("#phoneAsAuthField");
	enablePhoneNumberAsAuthField(phoneAsAuthFieldEl);
});

function enablePhoneNumberAsAuthField(phoneAsAuthFieldEl) {
	if (phoneAsAuthFieldEl.checked) {
		showEl(document.querySelectorAll(".auth-field-el"));
	} else {
		setDefaultAuthField();
		hideEl(document.querySelectorAll(".auth-field-el"));
	}
}
function setDefaultAuthField(defaultValue = "email") {
	let defaultAuthFieldEl = document.querySelector("#defaultAuthField");
	defaultAuthFieldEl.value = defaultValue;
}
</script>',
			],
		];
		
		return $fields;
	}
}
