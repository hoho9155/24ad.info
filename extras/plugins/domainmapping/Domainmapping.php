<?php

namespace extras\plugins\domainmapping;

/*
 * NOTE:
 *
 * By activating this plugin,
 *
 * 1. Developers: The 'lang/LANG/routes.php' file has to be (re)generated always without the 'settings.seo.multi_country_urls' option
 *    (that need to be always disabled while this plugin will be activated).
 *
 * 2. Administrators: Related to the #1, by installing the Domain Mapping plugin, you have to set a domain (or sub-domain) per country to prevent any SEO issue.
 *
 * 3. Developers: The cache files must be cleared (by installing the plugin & by saving any entry in the 'domains' table).
 *
 * 4. Developers: The robots.txt must be regenerated (by installing the plugin & by saving any entry in the 'domains' table).
 */

use App\Helpers\DBTool;
use App\Models\Setting;
use extras\plugins\domainmapping\app\Models\DomainMetaTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Prologue\Alerts\Facades\Alert;

class Domainmapping
{
	/**
	 * @return string
	 */
	public static function getAdminMenu(): string
	{
		$out = '<li class="sidebar-item">';
		$out .= '<a href="' . admin_url('domains') . '" class="sidebar-link">';
		$out .= '<i data-feather="star" class="feather-icon"></i> ';
		$out .= '<span class="hide-menu">' . trans('domainmapping::messages.Domains') . '</span>';
		$out .= '</a>';
		$out .= '</li>';
		
		return $out;
	}
	
	/**
	 * @return array
	 */
	public static function getOptions(): array
	{
		$options = [];
		
		$options[] = (object)[
			'name'     => trans('domainmapping::messages.Domains'),
			'url'      => admin_url('domains'),
			'btnClass' => 'btn-primary',
			'iClass'   => 'fa fa-star',
		];
		
		$setting = Setting::active()->where('key', 'domainmapping')->first();
		if (!empty($setting)) {
			$options[] = (object)[
				'name'     => mb_ucfirst(trans('admin.settings')),
				'url'      => admin_url('settings/' . $setting->id . '/edit'),
				'btnClass' => 'btn-info',
			];
		}
		
		return $options;
	}
	
	/**
	 * @return bool
	 */
	public static function installed(): bool
	{
		$cacheExpiration = 86400; // Cache for 1 day (60 * 60 * 24)
		
		return cache()->remember('plugins.domainmapping.installed', $cacheExpiration, function () {
			$setting = Setting::active()->where('key', 'domainmapping')->first();
			if (!empty($setting)) {
				if (
					Schema::hasTable('domains') &&
					Schema::hasTable('domain_settings') &&
					Schema::hasTable('domain_meta_tags') &&
					Schema::hasTable('domain_home_sections')
				) {
					return true;
				}
			}
			
			return false;
		});
	}
	
	/**
	 * @return bool
	 */
	public static function install(): bool
	{
		// Uninstall the plugin (without logout the admin user)
		self::uninstallWithoutLogout();
		
		try {
			// Perform the plugin SQL queries
			$updateSqlFile = plugin_path('domainmapping', 'database/sql/install.sql');
			if (file_exists($updateSqlFile)) {
				$sql = file_get_contents($updateSqlFile);
				$sql = str_replace('<<prefix>>', DB::getTablePrefix(), $sql);
				$sql = str_replace('__PREFIX__', DB::getTablePrefix(), $sql);
				DB::unprepared($sql);
			}
			
			// Create the plugin setting
			DB::statement('ALTER TABLE ' . DBTool::table((new Setting())->getTable()) . ' AUTO_INCREMENT = 1;');
			$pluginSetting = [
				'key'         => 'domainmapping',
				'name'        => 'Domain Mapping',
				//'value'     => null,
				'description' => 'Domain Mapping Plugin',
				'field'       => null,
				'parent_id'   => 0,
				'lft'         => 32,
				'rgt'         => 33,
				'depth'       => 1,
				'active'      => 1,
			];
			$setting = Setting::create($pluginSetting);
			if (empty($setting)) {
				return false;
			}
			
			if (Schema::hasTable('domains')) {
				// Remove the robots.txt file
				self::removeRobotsTxtFile();
				
				// Regenerate the routes.php file (by disabling the normal Multi-Country URLs support)
				self::regenerateLangRoutes(true);
				
				// Log out the admin user
				self::logout();
				
				// Update the /.env file to meet the plugin installation requirements
				self::updateEnvFile(true);
			}
			
			return true;
		} catch (\Throwable $e) {
			return false;
		}
	}
	
	/**
	 * @return bool
	 */
	public static function uninstall(): bool
	{
		try {
			cache()->forget('plugins.domainmapping.installed');
		} catch (\Throwable $e) {
		}
		
		try {
			// Uninstall the plugin (without logout the admin user)
			$res = self::uninstallWithoutLogout();
			if (!$res) {
				return false;
			}
			
			// Log out the admin user
			self::logout();
			
			// Update the /.env file to meet the plugin un-installation requirements
			self::updateEnvFile();
			
			return true;
		} catch (\Throwable $e) {
			$msg = 'ERROR: ' . $e->getMessage();
			Alert::error($msg)->flash();
		}
		
		return false;
	}
	
	
	// OTHER METHODS
	
	/**
	 * Uninstall the plugin without logout the admin user
	 *
	 * @return bool
	 */
	private static function uninstallWithoutLogout(): bool
	{
		try {
			// Remove plugin data
			$updateSqlFile = plugin_path('domainmapping', 'database/sql/uninstall.sql');
			if (file_exists($updateSqlFile)) {
				$sql = file_get_contents($updateSqlFile);
				$sql = str_replace('<<prefix>>', DB::getTablePrefix(), $sql);
				$sql = str_replace('__PREFIX__', DB::getTablePrefix(), $sql);
				DB::unprepared($sql);
			}
			
			// Remove the plugin setting
			$setting = Setting::where('key', 'domainmapping')->first();
			if (!empty($setting)) {
				$setting->delete();
			}
			
			// Remove the robots.txt file
			self::removeRobotsTxtFile();
			
			// Regenerate the routes.php file
			self::regenerateLangRoutes();
			
			return true;
		} catch (\Throwable $e) {
			$msg = 'ERROR: ' . $e->getMessage();
			Alert::error($msg)->flash();
		}
		
		return false;
	}
	
	/**
	 * Remove the robots.txt file (It will be re-generated automatically)
	 *
	 * @return void
	 */
	private static function removeRobotsTxtFile(): void
	{
		$robotsFile = public_path('robots.txt');
		if (File::exists($robotsFile)) {
			File::delete($robotsFile);
		}
	}
	
	/**
	 * Regenerate the "lang/[langCode]/routes.php" file
	 * (by disabling the normal Multi-Country URLs support)
	 *
	 * @param bool $installing
	 * @return void
	 */
	private static function regenerateLangRoutes(bool $installing = false): void
	{
		try {
			// Update in live the config vars related the Settings below before saving them.
			if ($installing) {
				config()->set('settings.seo.multi_country_urls', false);
			} else {
				$settings = Setting::where('key', 'seo')->first();
				if (!empty($settings)) {
					config()->set('settings.seo', $settings->value);
				}
			}
		} catch (\Throwable $e) {
			Alert::error($e->getMessage())->flash();
		}
	}
	
	/**
	 * Log out the admin user in order to allow him on its next login to Share Session between domains
	 *
	 * @return void
	 */
	public static function logout(): void
	{
		// Remove all session vars
		auth()->guard()->logout();
		request()->session()->flush();
		request()->session()->regenerate();
	}
	
	/**
	 * Update the /.env file to meet the plugin requirements
	 *
	 * @param bool $installing
	 * @return void
	 */
	public static function updateEnvFile(bool $installing = false): void
	{
		if ($installing) {
			if (config('settings.domainmapping.share_session')) {
				// Sharing Session between domains (Note: Only works sub-domains)
				if (!DotenvEditor::keyExists('SESSION_DOMAIN')) {
					$defaultDomain = getDomain(env('APP_URL', ''));
					DotenvEditor::setKey('SESSION_DOMAIN', '.' . $defaultDomain);
				}
			} else {
				// Stop Sharing Session between domains
				if (DotenvEditor::keyExists('SESSION_DOMAIN')) {
					DotenvEditor::deleteKey('SESSION_DOMAIN');
				}
			}
			
			// Add possibility to set countries page as homepage ('false' by default)
			if (!DotenvEditor::keyExists('DM_COUNTRIES_LIST_AS_HOMEPAGE')) {
				DotenvEditor::setKey('DM_COUNTRIES_LIST_AS_HOMEPAGE', 'false');
			}
		} else {
			// Stop Sharing Session between domains
			if (DotenvEditor::keyExists('SESSION_DOMAIN')) {
				DotenvEditor::deleteKey('SESSION_DOMAIN');
			}
			
			// Remove possibility to set countries page as homepage
			if (DotenvEditor::keyExists('DM_COUNTRIES_LIST_AS_HOMEPAGE')) {
				DotenvEditor::deleteKey('DM_COUNTRIES_LIST_AS_HOMEPAGE');
			}
		}
		
		// Change Laravel cookie name to prevent any session issue
		$randomId = '_' . substr(uniqid(rand(), true), 5, 5);
		$cookie = str(config('app.name', 'laravel'))->slug('_') . '_session' . $randomId;
		DotenvEditor::setKey('SESSION_COOKIE', $cookie);
		
		// Save the /.env file
		DotenvEditor::save();
	}
	
	/**
	 * Get meta tags from domain settings
	 *
	 * @param $page
	 * @return array|null
	 */
	public static function getMetaTag($page): ?array
	{
		$metaTag = ['title' => '', 'description' => '', 'keywords' => ''];
		
		// Get the current Language
		$languageCode = config('lang.abbr');
		$countryCode = config('country.code');
		
		// Get the Page's MetaTag
		$model = null;
		try {
			$cacheExpiration = (int)config('settings.optimization.cache_expiration', 86400);
			$cacheId = $countryCode . '.metaTag.' . $languageCode . '.' . $page;
			$model = cache()->remember($cacheId, $cacheExpiration, function () use ($languageCode, $page, $countryCode) {
				$page = strtolower($countryCode) . '_' . $page;
				$model = DomainMetaTag::query()
					->where('country_code', $countryCode)
					->where('page', $page)
					->first(['title', 'description', 'keywords']);
				
				if (!empty($model)) {
					$model->setLocale($languageCode);
					$model = $model->toArray();
				}
				
				return $model;
			});
		} catch (\Throwable $e) {
		}
		
		if (!empty($model)) {
			$metaTag = $model;
			
			$metaTag['title'] = getColumnTranslation($metaTag['title'], $languageCode);
			$metaTag['description'] = getColumnTranslation($metaTag['description'], $languageCode);
			$metaTag['keywords'] = getColumnTranslation($metaTag['keywords'], $languageCode);
			
			$metaTag['title'] = replaceGlobalPatterns($metaTag['title'], false);
			$metaTag['description'] = replaceGlobalPatterns($metaTag['description'], false);
			$metaTag['keywords'] = mb_strtolower(replaceGlobalPatterns($metaTag['keywords'], false));
			
			return array_values($metaTag);
		}
		
		return null;
	}
}
