<?php

namespace extras\plugins\domainmapping\app\Providers\AppService\ConfigTrait;

trait SecurityConfig
{
	private function updateSecurityConfig(?array $settings = []): void
	{
		if (empty(config('domainSettings.security'))) {
			return;
		}
		
		// CAPTCHA
		config()->set('captcha.option', config('domainSettings.security.captcha'));
		if (config('domainSettings.security.captcha') == 'custom') {
			if (
				config('domainSettings.security.captcha_length')
				&& config('domainSettings.security.captcha_length') >= 3
				&& config('domainSettings.security.captcha_length') <= 8
			) {
				config()->set('captcha.custom.length', config('domainSettings.security.captcha_length'));
			}
			if (
				config('domainSettings.security.captcha_width')
				&& config('domainSettings.security.captcha_width') >= 100
				&& config('domainSettings.security.captcha_width') <= 300
			) {
				config()->set('captcha.custom.width', config('domainSettings.security.captcha_width'));
			}
			if (
				config('domainSettings.security.captcha_height')
				&& config('domainSettings.security.captcha_height') >= 30
				&& config('domainSettings.security.captcha_height') <= 150
			) {
				config()->set('captcha.custom.height', config('domainSettings.security.captcha_height'));
			}
			if (config('domainSettings.security.captcha_quality')) {
				config()->set('captcha.custom.quality', config('domainSettings.security.captcha_quality'));
			}
			if (config('domainSettings.security.captcha_math')) {
				config()->set('captcha.custom.math', config('domainSettings.security.captcha_math'));
			}
			if (config('domainSettings.security.captcha_expire')) {
				config()->set('captcha.custom.expire', config('domainSettings.security.captcha_expire'));
			}
			if (config('domainSettings.security.captcha_encrypt')) {
				config()->set('captcha.custom.encrypt', config('domainSettings.security.captcha_encrypt'));
			}
			if (config('domainSettings.security.captcha_lines')) {
				config()->set('captcha.custom.lines', config('domainSettings.security.captcha_lines'));
			}
			if (config('domainSettings.security.captcha_bgImage')) {
				config()->set('captcha.custom.bgImage', config('domainSettings.security.captcha_bgImage'));
			}
			if (config('domainSettings.security.captcha_bgColor')) {
				config()->set('captcha.custom.bgColor', config('domainSettings.security.captcha_bgColor'));
			}
			if (config('domainSettings.security.captcha_sensitive')) {
				config()->set('captcha.custom.sensitive', config('domainSettings.security.captcha_sensitive'));
			}
			if (config('domainSettings.security.captcha_angle')) {
				config()->set('captcha.custom.angle', config('domainSettings.security.captcha_angle'));
			}
			if (config('domainSettings.security.captcha_sharpen')) {
				config()->set('captcha.custom.sharpen', config('domainSettings.security.captcha_sharpen'));
			}
			if (config('domainSettings.security.captcha_blur')) {
				config()->set('captcha.custom.blur', config('domainSettings.security.captcha_blur'));
			}
			if (config('domainSettings.security.captcha_invert')) {
				config()->set('captcha.custom.invert', config('domainSettings.security.captcha_invert'));
			}
			if (config('domainSettings.security.captcha_contrast')) {
				config()->set('captcha.custom.contrast', config('domainSettings.security.captcha_contrast'));
			}
		}
		
		// reCAPTCHA
		if (config('domainSettings.security.captcha') == 'recaptcha') {
			config()->set('recaptcha.version', config('domainSettings.security.recaptcha_version', 'v2'));
			if (config('recaptcha.version') == 'v3') {
				config()->set('recaptcha.site_key', config('domainSettings.security.recaptcha_v3_site_key'));
				config()->set('recaptcha.secret_key', config('domainSettings.security.recaptcha_v3_secret_key'));
			} else {
				config()->set('recaptcha.site_key', config('domainSettings.security.recaptcha_v2_site_key'));
				config()->set('recaptcha.secret_key', config('domainSettings.security.recaptcha_v2_secret_key'));
			}
			$recaptchaSkipIps = env('RECAPTCHA_SKIP_IPS', config('domainSettings.security.recaptcha_skip_ips', ''));
			$recaptchaSkipIpsArr = preg_split('#[:,;\s]+#ui', $recaptchaSkipIps);
			$recaptchaSkipIpsArr = array_filter(array_map('trim', $recaptchaSkipIpsArr));
			config()->set('recaptcha.skip_ip', $recaptchaSkipIpsArr);
		}
	}
}
