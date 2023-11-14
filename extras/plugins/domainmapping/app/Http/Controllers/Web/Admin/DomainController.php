<?php

namespace extras\plugins\domainmapping\app\Http\Controllers\Web\Admin;

use App\Models\Country;
use extras\plugins\domainmapping\app\Http\Requests\DomainRequest as StoreRequest;
use extras\plugins\domainmapping\app\Http\Requests\DomainRequest as UpdateRequest;
use extras\plugins\domainmapping\app\Models\Domain;
use App\Http\Controllers\Web\Admin\Panel\PanelController;
use Prologue\Alerts\Facades\Alert;

class DomainController extends PanelController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('extras\plugins\domainmapping\app\Models\Domain');
        $this->xPanel->setRoute(admin_uri('domains'));
        $this->xPanel->setEntityNameStrings(strtolower(trans('domainmapping::messages.Domain')), strtolower(trans('domainmapping::messages.Domains')));
        if (!request()->input('order')) {
            $this->xPanel->orderBy('country_code');
        }
        
        $this->xPanel->addButtonFromModelFunction('top', 'bulk_countries_sub_domain_button', 'bulkCountriesSubDomainButton', 'end');
        $this->xPanel->addButtonFromModelFunction('top', 'bulk_deletion_button', 'bulkDeletionButton', 'end');
		
        $this->xPanel->addButtonFromModelFunction('line', 'settings', 'settingsButton', 'beginning');
		$this->xPanel->addButtonFromModelFunction('line', 'meta_tags', 'metaTagsButton', 'beginning');
		$this->xPanel->addButtonFromModelFunction('line', 'home_sections', 'homeSectionsButton', 'beginning');
        
        // Filters
        // -----------------------
        $this->xPanel->addFilter(
            [
            'name'  => 'country',
            'type'  => 'select2',
            'label' => trans('domainmapping::messages.Country'),
        ],
            getCountries(),
            function ($value) {
                $this->xPanel->addClause('where', 'country_code', '=', $value);
            }
        );
        // -----------------------
        $this->xPanel->addFilter(
            [
            'name'  => 'host',
            'type'  => 'text',
            'label' => trans('domainmapping::messages.Host'),
        ],
            false,
            function ($value) {
                $this->xPanel->addClause('where', 'host', 'LIKE', "%$value%");
            }
        );
        // -----------------------
        
        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name'      => 'id',
            'label'     => '',
            'type'      => 'checkbox',
            'orderable' => false,
        ]);
        $this->xPanel->addColumn([
            'name'          => 'country_code',
            'label'         => trans("domainmapping::messages.Country"),
            'type'          => 'model_function',
            'function_name' => 'getCountryHtml',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'host',
            'label'         => trans("domainmapping::messages.Host") . ' (' . trans("domainmapping::messages.Base URL") . ')',
            'type'          => 'model_function',
            'function_name' => 'getDomainHtml',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'https',
            'label'         => trans("domainmapping::messages.HTTPS"),
            'type'          => 'model_function',
            'function_name' => 'getHttpsHtml',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => trans("domainmapping::messages.Active"),
            'type'          => 'model_function',
            'function_name' => 'getActiveHtml',
        ]);
        
        // FIELDS
        $this->xPanel->addField([
            'name' => 'id',
            'type' => 'hidden',
        ], 'update');
        $this->xPanel->addField([
            'name'      => 'country_code',
            'label'     => trans('domainmapping::messages.Country'),
            'type'      => 'select2',
            'attribute' => 'name',
            'model'     => 'App\Models\Country',
        ]);
        $this->xPanel->addField([
            'name'              => 'host',
            'label'             => trans("domainmapping::messages.Host"),
            'type'              => 'text',
            'attributes'        => [
                'placeholder' => trans("domainmapping::messages.host_example"),
            ],
            'hint'              => trans('domainmapping::messages.host_hint'),
            'wrapperAttributes' => [
                'class' => 'col-md-6',
            ],
        ]);
        $this->xPanel->addField([
            'name'              => 'https',
            'label'             => trans("domainmapping::messages.HTTPS"),
            'type'              => 'checkbox_switch',
            'wrapperAttributes' => [
                'class' => 'col-md-6',
                
            ],
        ]);
        $this->xPanel->addField([
            'name'    => 'active',
            'label'   => trans("domainmapping::messages.Active"),
            'type'    => 'checkbox_switch',
            'default' => 1,
        ]);
    }
    
    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }
    
    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
    
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createBulkCountriesSubDomain()
    {
        $success = false;
        
        try {
            $countries = Country::where('active', 1)->get();
            if ($countries->count() > 0) {
                // Get the default domain
                $defaultDomain = getDomain(env('APP_URL', ''));
                
                foreach ($countries as $country) {
                    if (Domain::where('country_code', $country->code)->count() <= 0) {
                        $domain = [
                            'country_code' => $country->code,
                            'host'         => strtolower($country->code) . '.' . $defaultDomain,
                            'https'        => 0,
                            'active'       => 1,
                        ];
                        $domain = Domain::create($domain);
                        $success = true;
                    }
                }
            }
            if ($success) {
                Alert::success(trans('domainmapping::messages.The sub-domains were been created'))->flash();
            } else {
                Alert::warning(trans('domainmapping::messages.Sub-domains have already been created'))->flash();
            }
        } catch (\Throwable $e) {
            Alert::error($e->getMessage())->flash();
        }
        
        return redirect()->back();
    }
}
