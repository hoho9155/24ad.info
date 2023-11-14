@php
	$post ??= [];
	
	$userIsAdmin = (
		auth()->check()
		&& auth()->user()->hasAllPermissions(\App\Models\Permission::getStaffPermissions())
	);
	$userIsTheListingOwner = (
		auth()->check()
		&& isset(auth()->user()->id, $post)
		&& auth()->user()->id == data_get($post, 'user.id')
	);
	$userIsNotTheListingOwner = (
		auth()->check()
		&& isset(auth()->user()->id, $post)
		&& auth()->user()->id != data_get($post, 'user.id')
	);
	$guestCanPublishComment = (!auth()->check() && config('settings.reviews.guests_comments'));
@endphp
<div class="tab-pane reviews-widget"
	 id="item-{{ config('plugins.reviews.name') }}"
	 role="tabpanel"
	 aria-labelledby="item-{{ config('plugins.reviews.name') }}-tab"
>
	<div class="row">
		
		@if (!empty($post))
			<div class="col-md-12 well" id="reviews-anchor">
				
				@if (isset($errors) && $errors->any())
					<div class="row pt-3">
						<div class="col-md-12">
							<div class="alert alert-danger alert-dismissible mb-0">
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
								<h5><strong>{{ trans('reviews::messages.There were errors while submitting this review') }}:</strong></h5>
								<ul class="list list-check">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
				@endif
				@if (session()->has('review_posted'))
					<div class="row pt-3">
						<div class="col-md-12">
							<div class="alert alert-success alert-dismissible mb-0">
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
								<p class="mb-0"><strong>{{ trans('reviews::messages.review_posted') }}</strong></p>
							</div>
						</div>
					</div>
				@endif
				@if (session()->has('review_removed'))
					<div class="row pt-3">
						<div class="col-md-12">
							<div class="alert alert-success alert-dismissible mb-0">
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
								<p class="mb-0"><strong>{{ trans('reviews::messages.Your review has been removed!') }}</strong></p>
							</div>
						</div>
					</div>
				@endif
				
				<div class="row pb-3" id="post-review-box">
					@if (!auth()->check() && !config('settings.reviews.guests_comments'))
						<div class="col-md-12 pb-3">
							<div class="row">
								<div class="col-12 text-center my-3">
									<strong>{{ trans('reviews::messages.Note') }}:</strong>
									{{ trans('reviews::messages.You must be logged in to post a review.') }}
								</div>
								<div class="col-12">
									<form action="{{ \App\Helpers\UrlGen::login() }}" method="post" class="m-0 p-0">
										{!! csrf_field() !!}
										<div class="row d-flex justify-content-center gx-1 gy-1">
											
											{{-- email --}}
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 auth-field-item">
												<div class="form-group">
													<input id="email" name="email"
														   type="text"
														   class="form-control"
														   placeholder="{{ t('email_address') }}"
													>
													@if (config('settings.sms.enable_phone_as_auth_field') == '1')
														<a href="" class="auth-field" data-auth-field="phone">{{ t('login_with_phone') }}</a>
													@endif
												</div>
											</div>
											
											{{-- phone --}}
											@if (config('settings.sms.enable_phone_as_auth_field') == '1')
												<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 auth-field-item">
													<div class="form-group">
														<input id="phone" name="phone"
															   type="tel"
															   class="form-control"
															   placeholder="{{ t('phone_number') }}"
														>
														<a href="" class="auth-field" data-auth-field="email">{{ t('login_with_email') }}</a>
														<input name="phone_country" type="hidden" value="{{ old('phone_country', config('country.code')) }}">
													</div>
												</div>
											@endif
											
											{{-- auth_field --}}
											<input name="auth_field" type="hidden" value="{{ old('auth_field', getAuthField()) }}">
											
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
												<div class="form-group">
													<input id="password" name="password"
														   type="password"
														   class="form-control"
														   placeholder="{{ t('password') }}"
														   autocomplete="new-password"
													>
												</div>
											</div>
											
											<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
												<button type="submit" class="btn btn-primary btn-block">{{ t('log_in') }}</button>
											</div>
											
											@if (config('captcha.option') && !empty(config('captcha.option')))
												<div class="col-12">
													<div class="row d-flex justify-content-center gx-1 gy-1 mt-2">
														<div class="col-xl-4 col-lg-6 col-md-6 col-sm-8 col-12">
															@include('layouts.inc.tools.captcha', ['noLabel' => true])
														</div>
													</div>
												</div>
											@endif
											
										</div>
									</form>
								</div>
							</div>
						</div>
					@else
						@if ($userIsNotTheListingOwner || $guestCanPublishComment)
							<div class="col-md-12">
								<form id="reviewsForm" method="POST" action="{{ url('posts/' . data_get($post, 'id') . '/reviews/create') }}">
									{!! csrf_field() !!}
									<input type="hidden" name="rating" id="rating">
									
									<?php $commentError = (isset($errors) && $errors->has('comment')) ? ' has-error' : ''; ?>
									<div class="row required mb-0{{ $commentError }}">
										<div class="col-md-12 pt-3 pl-3 pr-3 pb-0">
											<textarea name="comment"
													  id="comment"
													  rows="5"
													  style="min-height: 100px;"
													  class="form-control animated"
													  placeholder="{{ trans('reviews::messages.Enter your review here...') }}"
											>{{ old('comment') }}</textarea>
										</div>
									</div>
									
									<div class="form-group row">
										<div class="col-md-12 text-right">
											<div class="stars starrr" data-rating="{{ old('rating', 0) }}"></div>
											<button class="btn btn-success btn-lg" type="submit">{{ trans('reviews::messages.Leave a Review') }}</button>
										</div>
									</div>
								</form>
							</div>
						@endif
					
					@endif
				</div>
				
				@if ($userIsNotTheListingOwner || $guestCanPublishComment)
					<hr class="border-0 bg-secondary">
				@endif
				
				@php
					$reviewsApiResult = $reviewsApiResult ?? [];
					$messageReviews = data_get($reviewsApiResult, 'message') ?? null;
					$reviewsApiResult = (array)data_get($reviewsApiResult, 'result');
					$reviews = (array)data_get($reviewsApiResult, 'data');
					$totalReviews = (int)data_get($reviewsApiResult, 'meta.total', 0);
				@endphp
				@if (!empty($reviews) && $totalReviews > 0)
					@foreach($reviews as $review)
						@php
							$userIsTheCommentOwner = (auth()->check() && auth()->user()->id == data_get($review, 'user.id'));
						@endphp
						<div class="row comments">
							<div class="col-md-12">
								@for ($i=1; $i <= 5 ; $i++)
									<span class="{{ ($i <= data_get($review, 'rating')) ? 'fas' : 'far'}} fa-star"></span>
								@endfor
								
								<span class="rating-label">
									{{ data_get($review, 'user.name') ?? trans('reviews::messages.Anonymous') }}
									@if ($userIsTheCommentOwner || $userIsAdmin)
										@php
											$deleteReviewUrl = url('posts/' . data_get($post, 'id') . '/reviews/' . data_get($review, 'id') . '/delete');
										@endphp
										[<a href="{{ $deleteReviewUrl }}" class="confirm-simple-action">
											{{ trans('reviews::messages.Delete') }}
										</a>]
									@endif
								</span>
								<span class="float-end">{!! data_get($review, 'created_at_formatted') !!}</span>
								
								<p>{!! data_get($review, 'comment') !!}</p>
							
							</div>
						</div>
					@endforeach
					
					<nav class="mb-3">
						@include('vendor.pagination.api.bootstrap-4', ['apiResult' => $reviewsApiResult])
					</nav>
				@else
					@if ($userIsTheListingOwner)
						<p>{{ trans('reviews::messages.Your listing has no reviews yet.') }}</p>
					@else
						@if (auth()->check() || config('settings.reviews.guests_comments'))
							<p>{{ trans('reviews::messages.This listing has no reviews yet. Be the first to leave a review.') }}</p>
						@endif
					@endif
				@endif
			</div>
		@endif
	
	</div>
</div>

@section('after_styles')
	@parent
	<link href="{{ url('plugins/reviews/assets/js/starrr.css') }}" rel="stylesheet" type="text/css">
	<style>
		.items-details .tab-content {
			padding-top: 5px;
			padding-bottom: 5px;
		}
		.items-details .well {
			margin-bottom: 0;
			border: 0;
			background-color: #fafafa;
		}
		#item-reviews {
			margin-top: 0;
		}
		#item-reviews > div {
			padding: 0 10px;
		}
		/* Enhance the look of the textarea expanding animation */
		.reviews-widget .animated {
			-webkit-transition: height 0.2s;
			-moz-transition: height 0.2s;
			transition: height 0.2s;
		}
		.reviews-widget .stars {
			margin: 20px 0;
			font-size: 24px;
			color: #ffc32b;
		}
		.reviews-widget .stars a {
			color: #ffc32b;
		}
		.reviews-widget .comments span.fas.fa-star,
		.reviews-widget .comments span.far.fa-star {
			margin-top: 5px;
			font-size: 16px;
			@if (config('lang.direction') == 'rtl')
 				margin-left: -4px;
			@else
 				margin-right: -4px;
			@endif
		}
		.reviews-widget .comments .rating-label {
			margin-top: 5px;
			font-size: 16px;
			@if (config('lang.direction') == 'rtl')
 				margin-right: 4px;
			@else
 				margin-left: 4px;
			@endif
		}
		@media (min-width: 576px) {
			#post-review-box .form-group {
				margin-bottom: 0;
			}
			#post-review-box .form-control {
				width: 100%;
			}
		}
	</style>
@endsection

@section('after_scripts')
	@parent
	<script src="{{ url('plugins/reviews/assets/js/autosize.js') }}"></script>
	<script src="{{ url('plugins/reviews/assets/js/starrr.js') }}"></script>
	<script>
		$(document).ready(function () {
			{{-- Initialize the autosize plugin on the review text area --}}
			autosize($('#comment'));
			
			{{-- Bind the change event for the star rating - store the rating value in a hidden field --}}
			$('.starrr').starrr({
				change: function (e, value) {
					$('#rating').val(value);
				}
			});
		});
	</script>
@endsection
