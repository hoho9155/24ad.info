<?php

namespace extras\plugins\reviews\app\Helpers;

use App\Models\Post as PostModel;
use extras\plugins\reviews\app\Models\Review;

class Post
{
	/**
	 * @param $postId
	 * @return array
	 */
	public static function getReviews($postId): array
	{
		// Call API endpoint
		$endpoint = '/plugins/posts/' . $postId . '/reviews';
		$queryParams = [
			'embed' => 'user',
			'sort'  => 'created_at',
		];
		$queryParams = array_merge(request()->all(), $queryParams);
		$data = makeApiRequest('get', $endpoint, $queryParams);
		
		return is_array($data) ? $data : [];
	}
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	/**
	 * The way average rating is calculated (and stored) is by getting an average of all ratings,
	 * storing the calculated value in the rating_cache column (so that we don't have to do calculations later)
	 * and incrementing the rating_count column by 1
	 *
	 * @param \App\Models\Post $post
	 * @return void
	 */
	public static function recalculateRating(PostModel $post): void
	{
		$reviews = $post->reviews()->notSpam()->approved();
		$avgRating = $reviews->avg('rating');
		
		$avgRating = round($avgRating, 1);
		
		// Use valid data for MySQL
		$avgRating = str_replace(',', '.', $avgRating);
		$avgRating = preg_replace('/[^0-9.]/', '', $avgRating);
		
		$post->rating_cache = $avgRating;
		$post->rating_count = $reviews->count();
		$post->save();
	}
	
	/**
	 * Get average rating (from all the ratings received) by User
	 *
	 * @param \App\Models\Post $post
	 * @param $userId
	 * @return float|int
	 */
	public static function getUserRating(PostModel $post, $userId = null)
	{
		if (empty($userId)) {
			$userId = $post->user_id ?? -1;
		}
		
		$reviews = Review::query()
			->notSpam()
			->approved()
			->whereHas('post', fn($query) => $query->where('user_id', $userId));
		$avgRating = $reviews->avg('rating');
		
		$avgRating = round($avgRating, 1);
		
		// Use valid data for MySQL
		$avgRating = str_replace(',', '.', $avgRating);
		$avgRating = preg_replace('/[^0-9.]/', '', $avgRating);
		
		return (is_numeric($avgRating)) ? $avgRating : 0;
	}
	
	/**
	 * Count the number of ratings received by the User's posts
	 *
	 * @param \App\Models\Post $post
	 * @param $userId
	 * @return mixed
	 */
	public static function getCountUserRatings(PostModel $post, $userId = null)
	{
		if (empty($userId)) {
			$userId = $post->user_id ?? -1;
		}
		
		return $post->where('user_id', $userId)->sum('rating_count');
	}
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public static function reviews($builder)
	{
		return $builder->hasMany(Review::class, 'post_id');
	}
}
