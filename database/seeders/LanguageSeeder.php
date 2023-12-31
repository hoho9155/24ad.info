<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$entries = [
			[
				'abbr'                  => 'en',
				'locale'                => $this->getUtf8Locale('en_US'),
				'name'                  => 'English',
				'native'                => 'English',
				'flag'                  => 'flag-icon-gb',
				'app_name'              => 'english',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
				'active'                => '1',
				'default'               => '1',
				'parent_id'             => null,
				'lft'                   => '2',
				'rgt'                   => '3',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'fr',
				'locale'                => $this->getUtf8Locale('fr_FR'),
				'name'                  => 'French',
				'native'                => 'Français',
				'flag'                  => 'flag-icon-fr',
				'app_name'              => 'french',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'Do MMM YYYY',
				'datetime_format'       => 'Do MMM YYYY [à] H[h]mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '4',
				'rgt'                   => '5',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'es',
				'locale'                => $this->getUtf8Locale('es_ES'),
				'name'                  => 'Spanish',
				'native'                => 'Español',
				'flag'                  => 'flag-icon-es',
				'app_name'              => 'spanish',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D [de] MMMM [de] YYYY',
				'datetime_format'       => 'D [de] MMMM [de] YYYY HH:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '6',
				'rgt'                   => '7',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'ar',
				'locale'                => $this->getUtf8Locale('ar_SA'),
				'name'                  => 'Arabic',
				'native'                => 'العربية',
				'flag'                  => 'flag-icon-sa',
				'app_name'              => 'arabic',
				'script'                => 'Arab',
				'direction'             => 'rtl',
				'russian_pluralization' => '0',
				'date_format'           => 'DD/MMMM/YYYY',
				'datetime_format'       => 'DD/MMMM/YYYY HH:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '8',
				'rgt'                   => '9',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'pt',
				'locale'                => $this->getUtf8Locale('pt_PT'),
				'name'                  => 'Portuguese',
				'native'                => 'Português',
				'flag'                  => 'flag-icon-pt',
				'app_name'              => 'portuguese',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D [de] MMMM [de] YYYY',
				'datetime_format'       => 'D [de] MMMM [de] YYYY HH:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '10',
				'rgt'                   => '11',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'de',
				'locale'                => $this->getUtf8Locale('de_DE'),
				'name'                  => 'German',
				'native'                => 'Deutsch',
				'flag'                  => 'flag-icon-de',
				'app_name'              => 'german',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'dddd, D. MMMM YYYY',
				'datetime_format'       => 'dddd, D. MMMM YYYY HH:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '12',
				'rgt'                   => '13',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'it',
				'locale'                => $this->getUtf8Locale('it_IT'),
				'name'                  => 'Italian',
				'native'                => 'Italiano',
				'flag'                  => 'flag-icon-it',
				'app_name'              => 'italian',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY HH:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '14',
				'rgt'                   => '15',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'tr',
				'locale'                => $this->getUtf8Locale('tr_TR'),
				'name'                  => 'Turkish',
				'native'                => 'Türkçe',
				'flag'                  => 'flag-icon-tr',
				'app_name'              => 'turkish',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'DD MMMM YYYY dddd',
				'datetime_format'       => 'DD MMMM YYYY dddd HH:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '16',
				'rgt'                   => '17',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'ru',
				'locale'                => $this->getUtf8Locale('ru_RU'),
				'name'                  => 'Russian',
				'native'                => 'Русский',
				'flag'                  => 'flag-icon-ru',
				'app_name'              => 'russian',
				'script'                => 'Cyrl',
				'direction'             => 'ltr',
				'russian_pluralization' => '1',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY [ г.] H:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '18',
				'rgt'                   => '19',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'hi',
				'locale'                => $this->getUtf8Locale('hi_IN'),
				'name'                  => 'Hindi',
				'native'                => 'हिन्दी',
				'flag'                  => 'flag-icon-in',
				'app_name'              => 'hindi',
				'script'                => 'Devanagari',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY H:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '20',
				'rgt'                   => '21',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'bn',
				'locale'                => $this->getUtf8Locale('bn_BD'),
				'name'                  => 'Bengali',
				'native'                => 'বাংলা',
				'flag'                  => 'flag-icon-bd',
				'app_name'              => 'bengali',
				'script'                => 'Brahmic',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY H.mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '22',
				'rgt'                   => '23',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'zh',
				'locale'                => $this->getUtf8Locale('zh_CN'),
				'name'                  => 'Simplified Chinese',
				'native'                => '简体中文',
				'flag'                  => 'flag-icon-cn',
				'app_name'              => 'chinese',
				'script'                => 'Hans',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY H:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '24',
				'rgt'                   => '25',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'ja',
				'locale'                => $this->getUtf8Locale('ja_JP'),
				'name'                  => 'Japanese',
				'native'                => '日本語',
				'flag'                  => 'flag-icon-jp',
				'app_name'              => 'japanese',
				'script'                => 'Jpan',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY H:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '26',
				'rgt'                   => '27',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'th',
				'locale'                => $this->getUtf8Locale('th_TH'),
				'name'                  => 'Thai',
				'native'                => 'ไทย',
				'flag'                  => 'flag-icon-th',
				'app_name'              => 'thai',
				'script'                => 'Thai',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY H:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '28',
				'rgt'                   => '29',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'ro',
				'locale'                => $this->getUtf8Locale('ro_RO'),
				'name'                  => 'Romanian',
				'native'                => 'Română',
				'flag'                  => 'flag-icon-ro',
				'app_name'              => 'romanian',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY H:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '30',
				'rgt'                   => '31',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
			[
				'abbr'                  => 'ka',
				'locale'                => $this->getUtf8Locale('ka_GE'),
				'name'                  => 'Georgian',
				'native'                => 'ქართული',
				'flag'                  => 'flag-icon-ge',
				'app_name'              => 'georgian',
				'script'                => 'Geor',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'YYYY [წლის] DD MM',
				'datetime_format'       => 'YYYY [წლის] DD MMMM, dddd H:mm',
				'active'                => '1',
				'default'               => '0',
				'parent_id'             => null,
				'lft'                   => '32',
				'rgt'                   => '33',
				'depth'                 => '1',
				'deleted_at'            => null,
				'created_at'            => now()->format('Y-m-d H:i:s'),
				'updated_at'            => now()->format('Y-m-d H:i:s'),
			],
		];
		
		$tableName = (new Language())->getTable();
		foreach ($entries as $entry) {
			DB::table($tableName)->insert($entry);
		}
	}
	
	/**
	 * @param string $locale
	 * @return string
	 */
	private function getUtf8Locale(string $locale): string
	{
		// Limit the use of this method only for locales which often produce malfunctions
		// when they don't have their UTF-8 format. e.g. the Turkish language (tr_TR).
		$localesToFix = ['tr_TR'];
		if (!in_array($locale, $localesToFix)) {
			return $locale;
		}
		
		$localesList = getLocales('installed');
		
		// Return the given locale, if installed locales list cannot be retrieved from the server
		if (empty($localesList)) {
			return $locale;
		}
		
		// Return given locale, if the database charset is not utf-8
		$dbCharset = config('database.connections.' . config('database.default') . '.charset');
		if (!str_starts_with($dbCharset, 'utf8')) {
			return $locale;
		}
		
		$utf8LocaleFound = false;
		
		$codesetList = ['UTF-8', 'utf8'];
		foreach ($codesetList as $codeset) {
			$tmpLocale = $locale . '.' . $codeset;
			if (in_array($tmpLocale, $localesList, true)) {
				$locale = $tmpLocale;
				$utf8LocaleFound = true;
				break;
			}
		}
		
		if (!$utf8LocaleFound) {
			$codesetList = ['utf-8', 'UTF8'];
			foreach ($codesetList as $codeset) {
				$tmpLocale = $locale . '.' . $codeset;
				if (in_array($tmpLocale, $localesList, true)) {
					$locale = $tmpLocale;
					break;
				}
			}
		}
		
		return $locale;
	}
}
