<?php

namespace extras\plugins\reviews\app\Models;

use App\Helpers\Date;
use App\Models\BaseModel;
use App\Models\Post;
use App\Models\Scopes\LocalizedScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;

class Review extends BaseModel
{
	use Crud;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'reviews';
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	public $incrementing = false;
	protected $appends = ['created_at_formatted'];
	
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	//public $timestamps = false;
	
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
	protected $fillable = [
		'post_id',
		'user_id',
		'rating',
		'comment',
		'approved',
		'spam',
	];
	
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
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	protected static function boot()
	{
		parent::boot();
		
		static::addGlobalScope(new LocalizedScope());
	}
	
	/**
	 * @return string
	 */
	public function getPostTitleHtml(): string
	{
		$post = $this->post ?? null;
		
		return getPostUrl($post);
	}
	
	/**
	 * @return \Illuminate\Contracts\Translation\Translator|string
	 */
	public function getUserHtml()
	{
		if (!empty($this->user)) {
			return $this->user->name;
		}
		
		return trans('reviews::messages.Anonymous');
	}
	
	/**
	 * @return string
	 */
	public function getApprovedHtml()
	{
		if ($this->approved == 1) {
			return '<i class="admin-single-icon fa fa-toggle-on" aria-hidden="true"></i>';
		} else {
			return '<i class="admin-single-icon fa fa-toggle-off" aria-hidden="true"></i>';
		}
	}
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
	
	public function post()
	{
		return $this->belongsTo(Post::class, 'post_id');
	}
	
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeApproved($query)
	{
		return $query->where('approved', 1);
	}
	
	public function scopeSpam($query)
	{
		return $query->where('spam', 1);
	}
	
	public function scopeNotSpam($query)
	{
		return $query->where('spam', 0);
	}
	
	/*
	|--------------------------------------------------------------------------
	| ACCESSORS
	|--------------------------------------------------------------------------
	*/
	protected function createdAt(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				$value = new Carbon($value);
				$value->timezone(Date::getAppTimeZone());
				
				return $value;
			},
		);
	}
	
	protected function createdAtFormatted(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				$createdAt = $this->attributes['created_at'] ?? null;
				if (empty($createdAt)) {
					return null;
				}
				
				$value = new Carbon($createdAt);
				$value->timezone(Date::getAppTimeZone());
				
				return Date::formatFormNow($value);
			},
		);
	}
	
	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
