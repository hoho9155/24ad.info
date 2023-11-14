@php
    $post ??= [];
@endphp
@if (!empty($post))
    <div class="info-row reviews-widget ratings">
        @php
            $ratingCache = data_get($post, 'rating_cache', 0);
            $ratingCount = data_get($post, 'rating_count', 0);
        @endphp
        @for ($i=1; $i <= 5 ; $i++)
            <span class="{{ ($i <= $ratingCache) ? 'fas' : 'far' }} fa-star"></span>
        @endfor
        <span class="rating-label">
            {{ $ratingCount }} {{ trans_choice('reviews::messages.count_reviews', getPlural($ratingCount), [], config('app.locale')) }}
        </span>
    </div>
    
    @section('reviews_styles')
        <style>
            .reviews-widget > span.fas.fa-star,
            .reviews-widget > span.far.fa-star {
                margin-top: 5px;
                font-size: 16px;
                @if (config('lang.direction') == 'rtl')
                margin-left: -4px;
                @else
                margin-right: -4px;
                @endif
            }
            .reviews-widget > span.rating-label {
                margin-top: 5px;
                font-size: 14px;
                @if (config('lang.direction') == 'rtl')
                margin-right: 4px;
                @else
                margin-left: 4px;
                @endif
            }
            .reviews-widget > span.fas.fa-star,
            .reviews-widget > span.far.fa-star {
                color: #ffc32b;
            }
            
            .featured-list-slider span {
                display: inline;
            }
            .featured-list-slider .reviews-widget > span.fas.fa-star,
            .featured-list-slider .reviews-widget > span.far.fa-star {
                margin-top: 5px;
                font-size: 16px;
                @if (config('lang.direction') == 'rtl')
                margin-left: -4px;
                @else
                margin-right: -4px;
                @endif
            }
            .featured-list-slider .reviews-widget > span.rating-label {
                margin-top: 5px;
                @if (config('lang.direction') == 'rtl')
                margin-right: 4px;
                @else
                margin-left: 4px;
                @endif
            }
        </style>
    @endsection
@endif
