<?php

namespace extras\plugins\reviews;

use App\Helpers\DBTool;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Prologue\Alerts\Facades\Alert;

class Reviews
{
	/**
	 * @return string
	 */
	public static function getAdminMenu(): string
	{
		$out = '<li class="sidebar-item">';
		$out .= '<a href="' . admin_url('reviews') . '" class="sidebar-link">';
		$out .= '<i data-feather="message-square" class="feather-icon"></i> <span class="hide-menu">' . trans('reviews::messages.Reviews') . '</span>';
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
			'name'     => trans('reviews::messages.Reviews'),
			'url'      => admin_url('reviews'),
			'btnClass' => 'btn-primary',
			'iClass'   => 'fa fa-commenting-o',
		];
		$setting = Setting::active()->where('key', 'reviews')->first();
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
	public static function isPreInstalled(): bool
	{
		if (
			Schema::hasTable('reviews')
			&& Schema::hasColumn('posts', 'rating_cache')
			&& Schema::hasColumn('posts', 'rating_count')
		) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * @return bool
	 */
	public static function installed(): bool
	{
		$cacheExpiration = 86400; // Cache for 1 day (60 * 60 * 24)
		
		return cache()->remember('plugins.reviews.installed', $cacheExpiration, function () {
			$setting = Setting::active()->where('key', 'reviews')->first();
			if (!empty($setting)) {
				if (
					Schema::hasTable('reviews')
					&& Schema::hasColumn('posts', 'rating_cache')
					&& Schema::hasColumn('posts', 'rating_count')
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
		// Remove the plugin entry
		if (!self::isPreInstalled()) {
			self::uninstall();
		}
		
		try {
			// Perform the plugin SQL queries
			if (!self::isPreInstalled()) {
				$updateSqlFile = plugin_path('reviews', 'database/sql/install.sql');
				if (file_exists($updateSqlFile)) {
					$sql = file_get_contents($updateSqlFile);
					self::execSql($sql);
				}
				if (!Schema::hasColumn('posts', 'rating_cache')) {
					$sql = "ALTER TABLE `__PREFIX__posts` ADD `rating_cache` float(2, 1) unsigned NOT NULL DEFAULT '0.0' AFTER `visits`;";
					self::execSql($sql);
				}
				if (!Schema::hasColumn('posts', 'rating_count')) {
					$sql = "ALTER TABLE `__PREFIX__posts` ADD `rating_count` int(10) unsigned NOT NULL DEFAULT '0' AFTER `rating_cache`;";
					self::execSql($sql);
				}
			}
			
			// Create plugin setting
			DB::statement('ALTER TABLE ' . DBTool::table((new Setting())->getTable()) . ' AUTO_INCREMENT = 1;');
			$pluginSetting = [
				'key'         => 'reviews',
				'name'        => 'Reviews',
				//'value'     => null,
				'description' => 'Reviews System',
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
			Alert::error($e->getMessage())->flash();
		}
		
		return false;
	}
	
	/**
	 * @return bool
	 */
	public static function uninstall(): bool
	{
		try {
			cache()->forget('plugins.reviews.installed');
		} catch (\Throwable $e) {
		}
		
		try {
			// Remove plugin data
			if (Schema::hasColumn('posts', 'rating_cache')) {
				$sql = "ALTER TABLE `__PREFIX__posts` DROP `rating_cache`;";
				self::execSql($sql);
			}
			if (Schema::hasColumn('posts', 'rating_count')) {
				$sql = "ALTER TABLE `__PREFIX__posts` DROP `rating_count`;";
				self::execSql($sql);
			}
			if (Schema::hasTable('reviews')) {
				$sql = "DROP TABLE IF EXISTS `__PREFIX__reviews`;";
				self::execSql($sql);
			}
			
			// Remove the plugin setting
			$setting = Setting::where('key', 'reviews')->first();
			if (!empty($setting)) {
				$setting->delete();
			}
			
			return true;
		} catch (\Throwable $e) {
			Alert::error($e->getMessage())->flash();
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
