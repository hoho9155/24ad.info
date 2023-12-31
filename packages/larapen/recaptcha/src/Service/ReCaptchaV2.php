<?php

namespace Larapen\ReCaptcha\Service;

class ReCaptchaV2 extends ReCaptcha
{
	/**
	 * ReCaptchaV2 constructor.
	 *
	 * @param string $siteKey
	 * @param string $secretKey
	 * @param string $lang
	 */
	public function __construct(string $siteKey, string $secretKey, string $lang)
	{
		parent::__construct($siteKey, $secretKey, $lang, 'v2');
	}
	
	/**
	 * Write ReCAPTCHA HTML tag in your FORM
	 * Insert before </form> tag
	 *
	 * @return string
	 */
	public function htmlFormSnippet(): string
	{
		$out = '<div class="g-recaptcha" data-sitekey="' . $this->siteKey . '"></div>';
		
		return ($this->version == 'v2') ? $out : '';
	}
}
