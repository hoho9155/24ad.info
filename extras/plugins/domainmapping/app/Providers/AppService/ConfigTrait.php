<?php

namespace extras\plugins\domainmapping\app\Providers\AppService;

use App\Providers\AppService\ConfigTrait\MailConfig;
use App\Providers\AppService\ConfigTrait\SmsConfig;
use extras\plugins\domainmapping\app\Providers\AppService\ConfigTrait\SecurityConfig;
use extras\plugins\domainmapping\app\Models\Domain;
use extras\plugins\domainmapping\app\Models\DomainSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

trait ConfigTrait
{
	use MailConfig, SecurityConfig, SmsConfig;
	
	/**
	 * Setup Configs
	 */
	protected function setupConfigs(): void
	{
		// Create domains config var
		$this->createConfigForDomains();
		
		// Create Configs for DB Domain's Settings
		$this->createConfigForDomainSettings();
		
		// Updating...
		
		// Global
		$this->updateConfigs();
		
		// Mail
		$this->updateMailConfig(config('domainSettings.mail'));
		
		// SMS
		$this->updateSmsConfig(config('domainSettings.sms'));
		
		// Security
		$this->updateSecurityConfig();
	}
	
	/**
	 * Set the config vars
	 */
	private function createConfigForDomains(): void
	{
		// Get all the available domains
		$domains = [];
		try {
			if (Schema::hasTable('domains')) {
				$domains = cache()->remember('domains.active', $this->cacheExpiration, function () {
					return Domain::all();
				});
				
				if ($domains->count() > 0) {
					$domains = $domains->toArray();
					
					// Check & Set the HTTPS protocol
					$currentHost = getHost(url()->current());
					$currentHostArr = collect($domains)->firstWhere('host', $currentHost);
					if (isset($currentHostArr['https']) && $currentHostArr['https'] == 1) {
						// Force HTTPS protocol
						URL::forceScheme('https');
					}
				}
				
				// Disable the normal Multi-Country URLs support
				config()->set('settings.seo.multi_country_urls', false);
			}
		} catch (\Throwable $e) {
		}
		
		// Set in the config all the domains
		config()->set('domains', $domains);
	}
	
	/**
	 * Create domain's config vars from settings table
	 */
	private function createConfigForDomainSettings(): void
	{
		$countryCode = $this->getCountryCodeFromDomain();
		if (empty($countryCode)) {
			return;
		}
		
		// Check DB connection and catch it
		try {
			if (Schema::hasTable('domain_settings')) {
				// Get all settings from the database
				$cacheExpiration = (int)config('settings.optimization.cache_expiration', 86400);
				$cacheId = $countryCode . '.settings.active';
				$settings = cache()->remember($cacheId, $cacheExpiration, function () use ($countryCode) {
					return DomainSetting::where('country_code', $countryCode)->where('active', 1)->get();
				});
				
				// Bind all settings to the Laravel config, so you can call them like
				if ($settings->count() > 0) {
					foreach ($settings as $setting) {
						if (count($setting->value) > 0) {
							foreach ($setting->value as $subKey => $value) {
								if (isAdminPanel() && in_array($subKey, ['default_timezone'])) {
									continue;
								}
								
								$key = str_replace(strtolower($setting->country_code) . '_', '', $setting->key);
								config()->set('settings.' . $key . '.' . $subKey, $value);
								config()->set('domainSettings.' . $key . '.' . $subKey, $value);
							}
						}
					}
				}
			}
		} catch (\Throwable $e) {
		}
	}
	
	/**
	 * Update Global Configs
	 */
	private function updateConfigs(): void
	{
		// $appUrl = env('APP_URL');
		$currentBaseUrl = request()->root();
		
		if (!empty(config('domainSettings.social_auth'))) {
			// Facebook
			config()->set('services.facebook.client_id', config('domainSettings.social_auth.facebook_client_id'));
			config()->set('services.facebook.client_secret', config('domainSettings.social_auth.facebook_client_secret'));
			config()->set('services.facebook.redirect', $currentBaseUrl . '/auth/facebook/callback');
			
			// LinkedIn
			config()->set('services.linkedin-openid.client_id', config('domainSettings.social_auth.linkedin_client_id'));
			config()->set('services.linkedin-openid.client_secret', config('domainSettings.social_auth.linkedin_client_secret'));
			config()->set('services.linkedin-openid.redirect', $currentBaseUrl . '/auth/linkedin/callback');
			
			// Twitter (OAuth 2.0)
			config()->set('services.twitter-oauth-2.client_id', config('domainSettings.social_auth.twitter_oauth_2_client_id'));
			config()->set('services.twitter-oauth-2.client_secret', config('domainSettings.social_auth.twitter_oauth_2_client_secret'));
			config()->set('services.twitter-oauth-2.redirect', $currentBaseUrl . '/auth/twitter_oauth_2/callback');
			
			// Twitter (OAuth 1.0)
			config()->set('services.twitter.client_id', config('domainSettings.social_auth.twitter_client_id'));
			config()->set('services.twitter.client_secret', config('domainSettings.social_auth.twitter_client_secret'));
			config()->set('services.twitter.redirect', $currentBaseUrl . '/auth/twitter/callback');
			
			// Google
			config()->set('services.google.client_id', config('domainSettings.social_auth.google_client_id'));
			config()->set('services.google.client_secret', config('domainSettings.social_auth.google_client_secret'));
			config()->set('services.google.redirect', $currentBaseUrl . '/auth/google/callback');
		}
		
		// Google Maps
		if (!empty(config('domainSettings.other.googlemaps_key'))) {
			config()->set('services.googlemaps.key', config('domainSettings.other.googlemaps_key'));
		}
	}
}
