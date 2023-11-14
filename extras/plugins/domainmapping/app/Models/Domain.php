<?php

namespace extras\plugins\domainmapping\app\Models;

use App\Models\BaseModel;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\LocalizedScope;
use extras\plugins\domainmapping\app\Observers\DomainObserver;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Domain extends BaseModel
{
    use Crud;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'domains';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $appends = ['url'];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['country_code', 'host', 'https', 'active'];
    
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
    // protected $casts = [];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();
    
        Domain::observe(DomainObserver::class);
        
        static::addGlobalScope(new ActiveScope());
        static::addGlobalScope(new LocalizedScope());
    }
    
    public function bulkCountriesSubDomainButton($xPanel = false): string
    {
        $url = admin_url('domains/create_bulk_countries_sub_domains');
        
        $msg = trans('domainmapping::messages.Create bulk sub-domains based on countries codes.');
        $tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
	
		$out = '<a class="btn btn-success shadow" href="' . $url . '"' . $tooltip . '>';
        $out .= '<i class="fa fa-asterisk"></i> ';
        $out .= mb_ucfirst(trans('domainmapping::messages.Create Bulk Sub-Domains'));
        $out .= '</a>';
        
        return $out;
    }
    
    public function getCountryHtml()
    {
        $iconPath = 'images/flags/16/' . strtolower($this->country_code) . '.png';
        if (file_exists(public_path($iconPath))) {
	        $out = '<a href="' . dmUrl($this->country_code, '/', true, true) . '" target="_blank">';
            $out .= '<img src="' . url($iconPath) . getPictureVersion() . '" data-bs-toggle="tooltip" title="' . $this->country_code . '">';
            $out .= '</a>';
            
            return $out;
        } else {
            return $this->country_code;
        }
    }
    
    public function getDomainHtml(): string
    {
		return '<a href="' . $this->url . '" target="_blank">' . $this->url . '</a>';
    }
    
    public function getHttpsHtml(): string
    {
        if ($this->https == 1) {
            return '<i class="admin-single-icon fa fa-toggle-on" aria-hidden="true"></i>';
        } else {
            return '<i class="admin-single-icon fa fa-toggle-off" aria-hidden="true"></i>';
        }
    }
    
    public function getActiveHtml(): ?string
    {
        if ($this->active == 1) {
            return '<i class="admin-single-icon fa fa-toggle-on" aria-hidden="true"></i>';
        } else {
            return '<i class="admin-single-icon fa fa-toggle-off" aria-hidden="true"></i>';
        }
    }
	
	public function settingsButton($xPanel = false): string
	{
		$url = admin_url('domains/' . $this->country_code . '/settings');
		
		$msg = trans('domainmapping::messages.Settings of host', ['host' => $this->host]);
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-light" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa fa-cog"></i> ';
		$out .= mb_ucfirst(trans('domainmapping::messages.Settings'));
		$out .= '</a>';
		
		return $out;
	}
	
	public function homeSectionsButton($xPanel = false): string
	{
		$url = admin_url('domains/' . $this->country_code . '/homepage');
		
		$msg = trans('domainmapping::messages.Homepage of host', ['host' => $this->host]);
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-light" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa fa-home"></i> ';
		$out .= mb_ucfirst(trans('domainmapping::messages.Homepage'));
		$out .= '</a>';
		
		return $out;
	}
	
	public function metaTagsButton($xPanel = false)
	{
		$url = admin_url('domains/' . $this->country_code . '/meta_tags');
		
		$msg = trans('domainmapping::messages.Meta tags of host', ['host' => $this->host]);
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-light" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa fa-bookmark-o"></i> ';
		$out .= mb_ucfirst(trans('domainmapping::messages.Meta Tags'));
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
	protected function url(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				// Remove current protocol
				$value = preg_replace('#http[^:]*://#ui', '', $this->host);
				
				// Get the right protocol
				$protocol = ($this->https == 1) ? 'https' : 'http';
				
				// Use the right protocol instead
				return $protocol . '://' . $value;
			},
		);
	}
    
    /*
    |--------------------------------------------------------------------------
    | OTHER PRIVATE METHODS
    |--------------------------------------------------------------------------
    */
}
