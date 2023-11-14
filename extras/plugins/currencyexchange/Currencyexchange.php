<?php

namespace extras\plugins\currencyexchange;

use App\Helpers\DBTool;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Prologue\Alerts\Facades\Alert;

class Currencyexchange
{
	/**
	 * @return array
	 */
	public static function getOptions(): array
	{
		$options = [];
		$setting = Setting::active()->where('key', 'currencyexchange')->first();
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
		
		return cache()->remember('plugins.currencyexchange.installed', $cacheExpiration, function () {
			$setting = Setting::active()->where('key', 'currencyexchange')->first();
			if (!empty($setting)) {
				if (Schema::hasColumn('countries', 'currencies') && Schema::hasColumn('currencies', 'rate')) {
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
		// Uninstall the plugin
		if (Schema::hasColumn('countries', 'currencies') || Schema::hasColumn('currencies', 'rate')) {
			self::uninstall();
		}
		
		try {
			// Perform the plugin SQL queries
			if (!Schema::hasColumn('countries', 'currencies')) {
				$sql = "ALTER TABLE `__PREFIX__countries` ADD `currencies` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `equivalent_fips_code`;";
				self::execSql($sql);
			}
			if (!Schema::hasColumn('currencies', 'rate')) {
				$sql = "ALTER TABLE `__PREFIX__currencies` ADD `rate` float DEFAULT NULL COMMENT 'Rate related to the currency conversion base' AFTER `html_entities`;";
				self::execSql($sql);
			}
			
			// Create plugin setting
			DB::statement('ALTER TABLE ' . DBTool::table((new Setting())->getTable()) . ' AUTO_INCREMENT = 1;');
			$pluginSetting = [
				'key'         => 'currencyexchange',
				'name'        => 'Currency Exchange',
				//'value'     => null,
				'description' => 'Currency Exchange Plugin',
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
			cache()->forget('plugins.currencyexchange.installed');
		} catch (\Throwable $e) {
		}
		
		try {
			// Remove plugin session
			if (session()->has('curr')) {
				session()->forget('curr');
			}
			
			// Remove plugin data
			if (Schema::hasColumn('countries', 'currencies')) {
				$sql = "ALTER TABLE `__PREFIX__countries` DROP `currencies`;";
				self::execSql($sql);
			}
			if (Schema::hasColumn('currencies', 'rate')) {
				$sql = "ALTER TABLE `__PREFIX__currencies` DROP `rate`;";
				self::execSql($sql);
			}
			
			// Remove the plugin setting
			$setting = Setting::where('key', 'currencyexchange')->first();
			if (!empty($setting)) {
				$setting->delete();
			}
			
			return true;
		} catch (\Throwable $e) {
			$msg = 'ERROR: ' . $e->getMessage();
			Alert::error($msg)->flash();
		}
		
		return false;
	}
	
	/**
	 * @param string|null $sql
	 * @return void
	 */
	private static function execSql(?string $sql)
	{
		if (empty($sql)) {
			return;
		}
		
		$sql = str_replace('<<prefix>>', DB::getTablePrefix(), $sql);
		$sql = str_replace('__PREFIX__', DB::getTablePrefix(), $sql);
		DB::unprepared($sql);
	}
}
