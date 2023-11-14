@php
    $post ??= [];
@endphp
@if (!empty($post) && !empty(data_get($post, 'user')))
    @php
        $userRating = data_get($post, 'p_user_rating', 0);
        $countUserRatings = data_get($post, 'p_count_user_ratings', 0);
        $ratingUnitLabel = trans_choice('reviews::messages.count_ratings', getPlural($countUserRatings), [], config('app.locale'));
    @endphp
    <div class="rating">
        <div class="reviews-widget ratings">
            <p class="p-0 m-0">
                @for ($i=1; $i <= 5 ; $i++)
                    <span class="{{ ($i <= $userRating) ? 'fas' : 'far' }} fa-star"></span>
                @endfor
                <span class="rating-label">
                    {{ $countUserRatings  }} {{ mb_strtolower($ratingUnitLabel) }}
                </span>
            </p>
        </div>
    </div>
@endif
@section('after_styles')
    @parent
    @if (!empty($post) && !empty(data_get($post, 'user')))
    <style>
        .block-cell .rating {
            padding: 2px 2px 2px 3px !important;
            width: 130px !important;
            border-radius: 5px;
        }
        .block-cell .rating .reviews-widget span.fas.fa-star,
        .block-cell .rating .reviews-widget span.far.fa-star {
            margin-top: 2px !important;
            font-size: 14px !important;
            @if (config('lang.direction') == 'rtl')
            margin-left: -5px !important;
            @else
            margin-right: -5px !important;
            @endif
        }
        .block-cell .rating .reviews-widget .rating-label {
            margin-top: 0 !important;
            font-size: 10px !important;
            text-transform: none !important;
        }
        .block-cell .rating .reviews-widget span.fas.fa-star,
        .block-cell .rating .reviews-widget span.far.fa-star {
            color: #ffc32b;
        }
    </style>
    @endif
@endsection
