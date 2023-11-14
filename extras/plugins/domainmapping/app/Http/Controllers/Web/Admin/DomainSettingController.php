<?php

namespace extras\plugins\domainmapping\app\Http\Controllers\Web\Admin;

/*
------------------------------------------------------------------------------------
The "field" field value for "settings" table
------------------------------------------------------------------------------------
text            => {"name":"value","label":"Value","type":"text"}
textarea        => {"name":"value","label":"Value","type":"textarea"}
checkbox        => {"name":"value","label":"Activation","type":"checkbox"}
upload (image)  => {"name":"value","label":"Value","type":"image","upload":"true","disk":"uploads","default":"images/logo@2x.png"}
selectbox       => {"name":"value","label":"Value","type":"select_from_array","options":OPTIONS}
                => {"default":"Default","blue":"Blue","yellow":"Yellow","green":"Green","red":"Red"}
                => {"smtp":"SMTP","mailgun":"Mailgun","ses":"Amazon SES","mail":"PHP Mail","sendmail":"Sendmail"}
                => {"sandbox":"sandbox","live":"live"}
------------------------------------------------------------------------------------
*/

use App\Helpers\DBTool;
use App\Http\Controllers\Web\Admin\Traits\SettingsTrait;
use App\Http\Requests\Admin\SettingRequest as StoreRequest;
use App\Http\Requests\Admin\SettingRequest as UpdateRequest;
use App\Models\Setting;
use extras\plugins\domainmapping\app\Models\Domain;
use extras\plugins\domainmapping\app\Models\DomainSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Web\Admin\Panel\PanelController;
use Prologue\Alerts\Facades\Alert;

class DomainSettingController extends PanelController
{
	use SettingsTrait;
	
	public $countryCode = null;
	
	public function setup()
	{
		// Get the Country Code
		$this->countryCode = request()->segment(3);
		
		// Get the Country's name
		$domain = Domain::where('country_code', $this->countryCode)->firstOrFail();
		
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('extras\plugins\domainmapping\app\Models\DomainSetting');
		$this->xPanel->setRoute(admin_uri('domains/' . $this->countryCode . '/settings'));
		$this->xPanel->setEntityNameStrings(
			strtolower(trans('domainmapping::messages.Setting')) . ' &rarr; ' . '<strong>' . $domain->host . '</strong>',
			strtolower(trans('domainmapping::messages.Settings')) . ' &rarr; ' . '<strong>' . $domain->host . '</strong>'
		);
		
		$this->xPanel->enableParentEntity();
		$this->xPanel->setParentKeyField('country_code');
		$this->xPanel->addClause('where', 'country_code', '=', $this->countryCode);
		$this->xPanel->addClause('where', 'active', 1);
		$this->xPanel->setParentRoute(admin_uri('domains'));
		$this->xPanel->setParentEntityNameStrings(strtolower(trans('domainmapping::messages.Domain')), strtolower(trans('domainmapping::messages.Domains')));
		$this->xPanel->allowAccess(['parent', 'reorder']);
		$this->xPanel->denyAccess(['create', 'delete']);
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->setDefaultPageLength(100);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft');
			$this->xPanel->orderBy('id');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'generate_default_entries', 'generateDefaultEntriesBtn', 'end');
		$this->xPanel->addButtonFromModelFunction('top', 'reset_default_entries', 'resetDefaultEntriesBtn', 'end');
		$this->xPanel->removeButton('update');
		$this->xPanel->addButtonFromModelFunction('line', 'configure', 'configureButton', 'beginning');
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'          => 'name',
			'label'         => "Setting",
			'type'          => "model_function",
			'function_name' => 'getNameHtml',
		]);
		$this->xPanel->addColumn([
			'name'  => 'description',
			'label' => "",
		]);
		
		// FIELDS
		// ...
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud($request);
	}
	
	public function update(UpdateRequest $request)
	{
		$setting = DomainSetting::find(request()->segment(5));
		if (!empty($setting)) {
			// Get the right Setting
			$settingKey = str_replace(strtolower($setting->country_code) . '_', '', $setting->key);
			$settingClassName = str($settingKey)->camel()->ucfirst() . 'Setting';
			$settingNamespace = plugin_namespace('domainmapping') . '\app\Models\Setting\\';
			$settingClass = $settingNamespace . $settingClassName;
			if (class_exists($settingClass)) {
				if (method_exists($settingClass, 'passedValidation')) {
					$request = $settingClass::passedValidation($request);
				}
			} else {
				$settingNamespace = plugin_namespace($settingKey) . '\app\Models\Setting\\';
				$settingClass = $settingNamespace . $settingClassName;
				// Get the plugin's setting
				if (class_exists($settingClass)) {
					if (method_exists($settingClass, 'passedValidation')) {
						$request = $settingClass::passedValidation($request);
					}
				}
			}
		}
		
		return $this->updateTrait($request);
	}
	
	/**
	 * Generate Domain Settings
	 *
	 * @param $countryCode
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function generate($countryCode)
	{
		try {
			$keyField = 'key';
			$defaultEntriesKeys = DomainSetting::getDefaultEntriesKeys();
			
			// Remove all current settings
			$entries = DomainSetting::where('country_code', $countryCode)->get();
			$entries->each(function ($item, $key) {
				$item->delete();
			});
			
			// Reset permissions table ID auto-increment
			DB::statement('ALTER TABLE ' . DBTool::table(DomainSetting::query()->getModel()->getTable()) . ' AUTO_INCREMENT = 1;');
			
			// Get the main settings
			$mainEntries = Setting::where('active', 1)->get();
			
			// Copy the main settings
			$entriesKeys = [];
			$mainEntries->each(function ($item, $key) use ($keyField, &$entriesKeys, $countryCode, $defaultEntriesKeys) {
				if (in_array($item->$keyField, $defaultEntriesKeys)) {
					// Clear the settings elements
					$item = collect($item)
						->put($keyField, strtolower($countryCode) . '_' . $item->$keyField)
						->put('value', null)
						->put('field', null)
						->put('country_code', $countryCode)
						->reject(function ($v, $k) {
							return ($k == 'id');
						})
						->toArray();
					
					// Generate settings for the domain
					$entries = DomainSetting::create($item);
					if (!empty($entries)) {
						$entryKey = str_replace(strtolower($countryCode) . '_', '', $entries->$keyField);
						$entriesKeys[] = $entryKey;
					}
				}
			});
			
			// In case the entries are re-ordered,
			// and are no longer in the same order as the expected array's elements
			sort($entriesKeys);
			sort($defaultEntriesKeys);
			
			if ($entriesKeys == $defaultEntriesKeys) {
				$message = trans("domainmapping::messages.The settings were been generated successfully for this domain");
				Alert::success($message)->flash();
			} else {
				$message = trans("domainmapping::messages.No action has been performed");
				Alert::warning($message)->flash();
			}
		} catch (\Throwable $e) {
			Alert::warning($e->getMessage())->flash();
		}
		
		return redirect()->back();
	}
	
	/**
	 * Reset Domain Settings
	 *
	 * @param $countryCode
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function reset($countryCode)
	{
		try {
			$entries = DomainSetting::where('country_code', $countryCode)->get();
			if ($entries->count() > 0) {
				foreach ($entries as $entry) {
					$entry->delete();
				}
				
				Cache::flush();
				
				$message = trans("domainmapping::messages.The settings were been reset successfully for this domain");
				Alert::success($message)->flash();
			} else {
				$message = trans("domainmapping::messages.No action has been performed");
				Alert::warning($message)->flash();
			}
		} catch (\Throwable $e) {
			Alert::warning($e->getMessage())->flash();
		}
		
		return redirect()->back();
	}
}
