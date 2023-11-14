<?php

namespace extras\plugins\domainmapping\app\Http\Controllers\Web\Admin;

use App\Helpers\DBTool;
use App\Http\Controllers\Web\Admin\Traits\SettingsTrait;
use App\Models\HomeSection;
use extras\plugins\domainmapping\app\Models\Domain;
use extras\plugins\domainmapping\app\Models\DomainHomeSection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Web\Admin\Panel\PanelController;
use App\Http\Requests\Admin\Request as StoreRequest;
use App\Http\Requests\Admin\Request as UpdateRequest;
use Prologue\Alerts\Facades\Alert;

class DomainHomeSectionController extends PanelController
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
		$this->xPanel->setModel('extras\plugins\domainmapping\app\Models\DomainHomeSection');
		$this->xPanel->setRoute(admin_uri('domains/' . $this->countryCode . '/homepage'));
		$this->xPanel->setEntityNameStrings(
			strtolower(trans('domainmapping::messages.Homepage Section')) . ' &rarr; ' . '<strong>' . $domain->host . '</strong>',
			strtolower(trans('domainmapping::messages.Homepage Sections')) . ' &rarr; ' . '<strong>' . $domain->host . '</strong>'
		);
		
		$this->xPanel->enableParentEntity();
		$this->xPanel->addClause('where', 'country_code', '=', $this->countryCode);
		$this->xPanel->setParentRoute(admin_uri('domains'));
		$this->xPanel->setParentEntityNameStrings(strtolower(trans('domainmapping::messages.Domain')), strtolower(trans('domainmapping::messages.Domains')));
		$this->xPanel->allowAccess(['parent', 'reorder']);
		$this->xPanel->denyAccess(['create', 'delete']);
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->setDefaultPageLength(100);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft');
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
			'label'         => trans('admin.Section'),
			'type'          => 'model_function',
			'function_name' => 'getNameHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans('admin.Active'),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
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
		$section = DomainHomeSection::find(request()->segment(5));
		if (!empty($section)) {
			// Get the right Setting
			$sectionMethod = str_replace(strtolower($section->country_code) . '_', '', $section->method);
			$sectionClassName = str($sectionMethod)->camel()->ucfirst();
			$sectionNamespace = plugin_namespace('domainmapping') . '\app\Models\HomeSection\\';
			$sectionClass = $sectionNamespace . $sectionClassName;
			if (class_exists($sectionClass)) {
				if (method_exists($sectionClass, 'passedValidation')) {
					$request = $sectionClass::passedValidation($request);
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
			$appSlug = config('larapen.core.itemSlug');
			$keyField = 'method';
			$defaultEntriesKeys = DomainHomeSection::getDefaultEntriesKeys($appSlug);
			
			// Remove all current settings
			$entries = DomainHomeSection::where('country_code', $countryCode)->get();
			$entries->each(function ($item, $key) {
				$item->delete();
			});
			
			// Reset permissions table ID auto-increment
			DB::statement('ALTER TABLE ' . DBTool::table(DomainHomeSection::query()->getModel()->getTable()) . ' AUTO_INCREMENT = 1;');
			
			// Get the main settings
			$mainEntries = HomeSection::all();
			
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
						->reject(function($v, $k) {
							return ($k == 'id');
						})
						->toArray();
					
					// Generate settings for the domain
					$entries = DomainHomeSection::create($item);
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
				$message = trans("domainmapping::messages.The homepage sections settings were been generated successfully for this domain");
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
	 * Reset Homepage Sections
	 *
	 * @param $countryCode
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function reset($countryCode)
	{
		try {
			$entries = DomainHomeSection::where('country_code', $countryCode)->get();
			if ($entries->count() > 0) {
				foreach ($entries as $entry) {
					$entry->delete();
				}
				
				Cache::flush();
				
				$message = trans("domainmapping::messages.The homepage sections settings were been reset successfully for this domain");
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
