<?php

namespace extras\plugins\domainmapping\app\Models;

use App\Helpers\Files\Storage\StorageDisk;
use App\Models\BaseModel;
use extras\plugins\domainmapping\app\Observers\DomainSettingObserver;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DomainSetting extends BaseModel
{
	use Crud;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'domain_settings';
	
	protected $fakeColumns = ['value'];
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = false;
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $guarded = ['id'];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id', 'country_code', 'key', 'name', 'value', 'description', 'field', 'parent_id', 'lft', 'rgt', 'depth', 'active'];
	
	/**
	 * The attributes that should be hidden for arrays
	 *
	 * @var array
	 */
	// protected $hidden = [];
	
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'value' => 'array',
	];
	
	// Available Settings Keys
	private static $defaultEntriesKeys = [
		'app',
		'style',
		'list',
		'single',
		'mail',
		'sms',
		'seo',
		'geo_location',
		'security',
		'social_auth',
		'social_link',
		'other',
		'footer'
	];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	protected static function boot()
	{
		parent::boot();
		
		DomainSetting::observe(DomainSettingObserver::class);
	}
	
	public static function getDefaultEntriesKeys(): array
	{
		return self::$defaultEntriesKeys;
	}
	
	public function defaultEntriesExist(): bool
	{
		$keyField = 'key';
		$countryCode = request()->segment(3);
		
		// Available Settings Keys
		$defaultEntriesKeys = collect(self::getDefaultEntriesKeys())->map(function ($item, $key) use ($countryCode) {
			return strtolower($countryCode) . '_' . $item;
		})->toArray();
		
		// Check if domain settings exist
		$settings = self::where('country_code', $countryCode)->where('active', 1)->whereIn($keyField, $defaultEntriesKeys)->get();
		if ($settings->count() > 0) {
			$settingsKeys = $settings->keyBy($keyField)->keys()->toArray();
			
			// In case the entries are re-ordered,
			// and are no longer in the same order as the expected array's elements
			sort($settingsKeys);
			sort($defaultEntriesKeys);
			
			if ($settingsKeys == $defaultEntriesKeys) {
				return true;
			}
		}
		
		return false;
	}
	
	public function generateDefaultEntriesBtn($xPanel = false): ?string
	{
		if ($this->defaultEntriesExist()) {
			return null;
		}
		
		$countryCode = request()->segment(3);
		$url = admin_url('domains/' . $countryCode . '/settings/generate');
		
		$msg = trans('domainmapping::messages.Generate settings entries to customize this domain');
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		// Button
		$out = '<a class="btn btn-info shadow" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa fa-cog"></i> ';
		$out .= trans('domainmapping::messages.Generate settings entries');
		$out .= '</a>';
		
		return $out;
	}
	
	public function resetDefaultEntriesBtn($xPanel = false): ?string
	{
		if (!$this->defaultEntriesExist()) {
			return null;
		}
		
		$countryCode = request()->segment(3);
		$url = admin_url('domains/' . $countryCode . '/settings/reset');
		
		$msg = trans('domainmapping::messages.Remove the settings & customizations for this domain');
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		// Button
		$out = '<a class="btn btn-danger shadow" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa fa-remove"></i> ';
		$out .= trans('domainmapping::messages.Remove the settings');
		$out .= '</a>';
		
		return $out;
	}
	
	public function getNameHtml(): string
	{
		$currentUrl = preg_replace('#/(search)$#', '', url()->current());
		$url = $currentUrl . '/' . $this->getKey() . '/edit';
		
		return '<a href="' . $url . '">' . $this->name . '</a>';
	}
	
	public function configureButton($xPanel = false): string
	{
		$url = admin_url('domains/' . $this->country_code . '/settings/' . $this->id . '/edit');
		
		$msg = trans('admin.configure_entity', ['entity' => $this->name]);
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-primary" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa fa-cog"></i> ';
		$out .= mb_ucfirst(trans('admin.Configure'));
		$out .= '</a>';
		
		return $out;
	}
	
	/*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
	
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeActive($builder)
	{
		return $builder->where('active', 1);
	}
	
	/*
	|--------------------------------------------------------------------------
	| ACCESSORS | MUTATORS
	|--------------------------------------------------------------------------
	*/
	protected function field(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				$diskName = StorageDisk::getDiskName();
				
				// Get 'field' field value
				$value = jsonToArray($value);
				
				$breadcrumb = trans('admin.Admin panel') . ' &rarr; '
					. mb_ucwords(trans('domainmapping::messages.Domains')) . ' &rarr; '
					. mb_ucwords(trans('domainmapping::messages.Settings')) . ' &rarr; ';
				
				$formTitle = [
					[
						'name'         => 'group_name',
						'type'         => 'custom_html',
						'value'        => '<h2 class="setting-group-name">' . $this->name . '</h2>',
					],
					[
						'name'         => 'group_breadcrumb',
						'type'         => 'custom_html',
						'value'        => '<p class="setting-group-breadcrumb">' . $breadcrumb . $this->name . '</p>',
					],
				];
				
				// Handle 'field' field value
				// Get the right Setting
				$settingKey = str_replace(strtolower($this->country_code) . '_', '', $this->key);
				$settingClassName = str($settingKey)->camel()->ucfirst() . 'Setting';
				$settingNamespace = plugin_namespace('domainmapping') . '\app\Models\Setting\\';
				$settingClass = $settingNamespace . $settingClassName;
				if (class_exists($settingClass)) {
					if (method_exists($settingClass, 'getFields')) {
						$value = $settingClass::getFields($diskName);
					}
				} else {
					$settingNamespace = plugin_namespace($settingKey) . '\app\Models\Setting\\';
					$settingClass = $settingNamespace . $settingClassName;
					// Get the plugin's setting
					if (class_exists($settingClass)) {
						if (method_exists($settingClass, 'getFields')) {
							$value = $settingClass::getFields($diskName);
						}
					}
				}
				
				return array_merge($formTitle, $value);
			},
		);
	}
	
	protected function value(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => $this->getValue($value),
			set: fn ($value) => $this->setValue($value),
		);
	}
	
	/*
	|--------------------------------------------------------------------------
	| OTHER PRIVATE METHODS
	|--------------------------------------------------------------------------
	*/
	private function getValue($value)
	{
		$disk = StorageDisk::getDisk();
		
		// Hide all these fake field content
		$hiddenValues = [
			'smtp_username',
			'smtp_password',
			'mailgun_secret',
			'mailgun_username',
			'mailgun_password',
			'postmark_token',
			'postmark_username',
			'postmark_password',
			'ses_key',
			'ses_secret',
			'ses_username',
			'ses_password',
			'mandrill_secret',
			'mandrill_username',
			'mandrill_password',
			'sparkpost_secret',
			'sparkpost_username',
			'sparkpost_password',
			'sendmail_username',
			'sendmail_password',
			'vonage_key',
			'vonage_secret',
			'twilio_username',
			'twilio_password',
			'twilio_auth_token',
			'twilio_account_sid',
			'twilio_sms_service_sid',
			'recaptcha_v2_site_key',
			'recaptcha_v2_secret_key',
			'recaptcha_v3_site_key',
			'recaptcha_v3_secret_key',
			'recaptcha_site_key',
			'recaptcha_secret_key',
			'stripe_secret',
			'paypal_username',
			'paypal_password',
			'paypal_signature',
			'facebook_client_id',
			'facebook_client_secret',
			'linkedin_client_id',
			'linkedin_client_secret',
			'twitter_client_id',
			'twitter_client_secret',
			'google_client_id',
			'google_client_secret',
			'google_maps_key',
			'fixer_access_key',
			'currency_layer_access_key',
			'open_exchange_rates_app_id',
			'currency_data_feed_api_key',
			'forge_api_key',
			'xignite_token',
		];
		
		// Get 'value' field value
		$value = jsonToArray($value);
		
		// Handle 'value' field value
		// Get the right Setting
		$settingKey = str_replace(strtolower($this->country_code) . '_', '', $this->key);
		$settingClassName = str($settingKey)->camel()->ucfirst() . 'Setting';
		$settingNamespace = plugin_namespace('domainmapping') . '\app\Models\Setting\\';
		$settingClass = $settingNamespace . $settingClassName;
		if (class_exists($settingClass)) {
			if (method_exists($settingClass, 'getValues')) {
				$value = $settingClass::getValues($value, $disk);
			}
		} else {
			$settingNamespace = plugin_namespace($settingKey) . '\app\Models\Setting\\';
			$settingClass = $settingNamespace . $settingClassName;
			// Get the plugin's setting
			if (class_exists($settingClass)) {
				if (method_exists($settingClass, 'getValues')) {
					$value = $settingClass::getValues($value, $disk);
				}
			}
		}
		
		// Demo: Secure some Data (Applied for all Entries)
		if (isAdminPanel() && isDemoDomain()) {
			foreach ($value as $key => $item) {
				if (!in_array(request()->segment(2), ['password', 'login'])) {
					if (in_array($key, $hiddenValues)) {
						$value[$key] = '************************';
					}
				}
			}
		}
		
		return $value;
	}
	
	private function setValue($value)
	{
		if (is_null($value)) {
			return null;
		}
		
		// Get value
		$value = jsonToArray($value);
		
		// Handle 'value' field value
		// Get the right Setting
		$settingKey = str_replace(strtolower($this->country_code) . '_', '', $this->key);
		$settingClassName = str($settingKey)->camel()->ucfirst() . 'Setting';
		$settingNamespace = plugin_namespace('domainmapping') . '\app\Models\Setting\\';
		$settingClass = $settingNamespace . $settingClassName;
		if (class_exists($settingClass)) {
			if (method_exists($settingClass, 'setValues')) {
				$value = $settingClass::setValues($value, $this);
			}
		} else {
			$settingNamespace = plugin_namespace($settingKey) . '\app\Models\Setting\\';
			$settingClass = $settingNamespace . $settingClassName;
			// Get the plugin's setting
			if (class_exists($settingClass)) {
				if (method_exists($settingClass, 'setValues')) {
					$value = $settingClass::setValues($value, $this);
				}
			}
		}
		
		// Make sure that setting array contains only string, numeric or null elements
		$value = settingArrayElements($value);
		
		return (!empty($value)) ? json_encode($value) : null;
	}
}
