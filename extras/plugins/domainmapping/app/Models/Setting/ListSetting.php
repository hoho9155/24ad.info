<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

use App\Helpers\DBTool;

class ListSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['display_mode'] = 'make-grid';
			$value['items_per_page'] = '12';
			$value['cities_extended_searches'] = '1';
			$value['search_distance_max'] = '500';
			$value['search_distance_default'] = '50';
			$value['search_distance_interval'] = '100';
			
		} else {
			
			if (!array_key_exists('display_mode', $value)) {
				$value['display_mode'] = 'make-grid';
			}
			if (!array_key_exists('items_per_page', $value)) {
				$value['items_per_page'] = '12';
			}
			if (!array_key_exists('cities_extended_searches', $value)) {
				$value['cities_extended_searches'] = '1';
			}
			if (!array_key_exists('search_distance_max', $value)) {
				$value['search_distance_max'] = '500';
			}
			if (!array_key_exists('search_distance_default', $value)) {
				$value['search_distance_default'] = '50';
			}
			if (!array_key_exists('search_distance_interval', $value)) {
				$value['search_distance_interval'] = '100';
			}
			
		}
		
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName)
	{
		$fields = [
			[
				'name'  => 'separator_1',
				'type'  => 'custom_html',
				'value' => trans('admin.list_html_displaying'),
			],
		];
		
		if (config('larapen.core.itemSlug') == 'laraclassifier') {
			$lcFields = [
				[
					'name'              => 'display_mode',
					'label'             => trans('admin.Listing Page Display Mode'),
					'type'              => 'select2_from_array',
					'options'           => [
						'make-grid'    => 'Grid',
						'make-list'    => 'List',
						'make-compact' => 'Compact',
					],
					'attributes' => [
						'id'       => 'displayMode',
						'onchange' => 'getDisplayModeFields(this)',
					],
					'wrapperAttributes' => [
						'class' => 'col-md-6',
					],
				],
				[
					'name'              => 'grid_view_cols',
					'label'             => trans('admin.Grid View Columns'),
					'type'              => 'select2_from_array',
					'options'           => [
						4 => '4',
						3 => '3',
						2 => '2',
					],
					'wrapperAttributes' => [
						'class' => 'col-md-6 make-grid',
					],
				],
			];
			
			$fields = array_merge($fields, $lcFields);
		}
		
		$fields = array_merge($fields, [
			[
				'name'              => 'items_per_page',
				'label'             => trans('admin.Items per page'),
				'type'              => 'text',
				'hint'              => trans('admin.Number of items per page'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'left_sidebar',
				'label'             => trans('admin.Listing Page Left Sidebar'),
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				
				],
			],
			[
				'name'              => 'show_cats_in_top',
				'label'             => trans('admin.show_cats_in_top_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.show_cats_in_top_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'show_listings_tags',
				'label'             => trans('admin.show_listings_tags_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('admin.show_listings_tags_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_2',
				'type'  => 'custom_html',
				'value' => trans('admin.list_html_distance'),
			],
			[
				'name'              => 'cities_extended_searches',
				'label'             => trans('admin.cities_extended_searches_label'),
				'type'              => 'checkbox_switch',
				'attributes' => [
					'id'       => 'extendedSearches',
					'onclick' => 'getExtendedSearchesFields(this)',
				],
				'hint'              => trans('admin.cities_extended_searches_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'search_distance_max',
				'label'             => trans('admin.Max Search Distance'),
				'type'              => 'select2_from_array',
				'options'           => [
					1000 => '1000',
					900  => '900',
					800  => '800',
					700  => '700',
					600  => '600',
					500  => '500',
					400  => '400',
					300  => '300',
					200  => '200',
					100  => '100',
					50   => '50',
					0    => '0',
				],
				'hint'              => trans('admin.Max search radius distance'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 extended',
				],
			],
			[
				'name'  => 'separator_3',
				'type'  => 'custom_html',
				'value' => '<div style="clear: both;"></div>',
				'wrapperAttributes' => [
					'class' => 'col-md-12 extended',
				],
			],
			[
				'name'              => 'search_distance_default',
				'label'             => trans('admin.Default Search Distance'),
				'type'              => 'select2_from_array',
				'options'           => [
					200 => '200',
					100 => '100',
					50  => '50',
					25  => '25',
					20  => '20',
					10  => '10',
					0   => '0',
				],
				'hint'              => trans('admin.Default search radius distance'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 extended',
				],
			],
			[
				'name'              => 'search_distance_interval',
				'label'             => trans('admin.Distance Interval'),
				'type'              => 'select2_from_array',
				'options'           => [
					250 => '250',
					200 => '200',
					100 => '100',
					50  => '50',
					25  => '25',
					20  => '20',
					10  => '10',
					5   => '5',
				],
				'hint'              => trans('admin.The interval between filter distances'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 extended',
				],
			],
			
			[
				'name'  => 'javascript',
				'type'  => 'custom_html',
				'value' => '<script>
docReady(function() {
	let displayModeEl = document.querySelector("#displayMode");
	getDisplayModeFields(displayModeEl);
	
	let extendedSearchesEl = document.querySelector("#extendedSearches");
	getExtendedSearchesFields(extendedSearchesEl);
});

function getDisplayModeFields(displayModeEl) {
	let displayModeElValue = displayModeEl.value;
	
	hideEl(document.querySelectorAll(".make-grid"));
	
	if (displayModeElValue === "make-grid") {
		showEl(document.querySelectorAll(".make-grid"));
	}
}
function getExtendedSearchesFields(extendedSearchesEl) {
	if (extendedSearchesEl.checked) {
		showEl(document.querySelectorAll(".extended"));
	} else {
		hideEl(document.querySelectorAll(".extended"));
	}
}
</script>',
			],
		]);
		
		return $fields;
	}
	
	/**
	 * @return array
	 */
	private static function distanceCalculationFormula()
	{
		$array = [
			'haversine'  => trans('admin.haversine_formula'),
			'orthodromy' => trans('admin.orthodromy_formula'),
		];
		if (DBTool::isMySqlMinVersion('5.7.6') && !DBTool::isMariaDB()) {
			$array['ST_Distance_Sphere'] = trans('admin.mysql_spherical_calculation');
		}
		
		return $array;
	}
}
