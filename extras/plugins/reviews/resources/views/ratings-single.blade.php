@php
    $post ??= [];
@endphp
@if (!empty($post))
    <div class="reviews-widget ratings">
        <p>
            @php
                $ratingCache = data_get($post, 'rating_cache', 0);
            @endphp
            @for ($i=1; $i <= 5 ; $i++)
                <span class="{{ ($i <= data_get($post, 'rating_cache')) ? 'fas' : 'far' }} fa-star"></span>
            @endfor
            <span class="rating-label">
                @php
                    $ratingCacheFormat = number_format($ratingCache, 1);
					$ratingCacheFormatInt = number_format($ratingCache);
                @endphp
                {{ $ratingCacheFormat }} {{ trans_choice('reviews::messages.count_stars', getPlural($ratingCacheFormatInt), [], config('app.locale')) }}
            </span>
        </p>
    </div>
    
    @section('after_styles')
        @parent
        <style>
            .reviews-widget span.fas.fa-star,
            .reviews-widget span.far.fa-star {
                margin-top: 5px;
                font-size: 18px;
                @if (config('lang.direction') == 'rtl')
                margin-left: -5px;
                @else
                margin-right: -5px;
                @endif
            }
            .reviews-widget .rating-label {
                margin-top: 5px;
                font-size: 16px;
                @if (config('lang.direction') == 'rtl')
                margin-right: 6px;
                @else
                margin-left: 6px;
                @endif
            }
            .reviews-widget span.fas.fa-star,
            .reviews-widget span.far.fa-star {
                color: #ffc32b;
            }
        </style>
    @endsection
@endif
