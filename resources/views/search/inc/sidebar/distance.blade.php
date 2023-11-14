<?php
$qLocation = request()->get('location');
$isDistanceFilterCanBeDisplayed = !empty($qLocation);
$qDistance = request()->get('distance');
if (empty($qDistance)) $qDistance = config('settings.list.search_distance_default', 0);
?>
@if ($isDistanceFilterCanBeDisplayed)
	{{-- Distance --}}
	<div class="block-title has-arrow sidebar-header">
		<h5>
			<span class="fw-bold">
				Distance
			</span>
		</h5>
	</div>
	<div class="block-content list-filter number-range-slider-wrapper">
		<form role="form" class="form-inline" action="{{ request()->url() }}" method="GET">
			@foreach(request()->except(['page', 'distance', '_token']) as $key => $value)
				@if (is_array($value))
					@foreach($value as $k => $v)
						@if (is_array($v))
							@foreach($v as $ik => $iv)
								@continue(is_array($iv))
								<input type="hidden" name="{{ $key.'['.$k.']['.$ik.']' }}" value="{{ $iv }}">
							@endforeach
						@else
							<input type="hidden" name="{{ $key.'['.$k.']' }}" value="{{ $v }}">
						@endif
					@endforeach
				@else
					<input type="hidden" name="{{ $key }}" value="{{ $value }}">
				@endif
			@endforeach
			<div class="row px-1 gx-1 gy-1">
				<div class="col-12 mb-3 number-range-slider" id="distanceRangeSlider"></div>
				<div class="col-lg-4 col-md-12 col-sm-12 d-flex">
					<input type="number" min="0" id="distance" name="distance" class="form-control" placeholder="Distance" value="{{ $qDistance }}">
					<span class="align-self-center ms-1">{{ getDistanceUnit() }}</span>
				</div>

				<div class="col-lg-6 offset-lg-2 col-md-12 col-sm-12">
					<button class="btn btn-default btn-block" type="submit">{{ t('go') }}</button>
				</div>
			</div>
		</form>
	</div>
	<div style="clear:both"></div>
@endif

@section('after_scripts')
	@parent
	
	@if ($isDistanceFilterCanBeDisplayed)
		<link href="{{ url('assets/plugins/noUiSlider/15.5.0/nouislider.css') }}" rel="stylesheet">
		<style>
			/* Hide Arrows From Input Number */
			/* Chrome, Safari, Edge, Opera */
			.number-range-slider-wrapper input::-webkit-outer-spin-button,
			.number-range-slider-wrapper input::-webkit-inner-spin-button {
				-webkit-appearance: none;
				margin: 0;
			}
			/* Firefox */
			.number-range-slider-wrapper input[type=number] {
				-moz-appearance: textfield;
			}
		</style>
	@endif
@endsection
@section('after_scripts')
	@parent
	@if ($isDistanceFilterCanBeDisplayed)
		<script src="{{ url('assets/plugins/noUiSlider/15.5.0/nouislider.js') }}"></script>
		@php
			$minDistance = 1;
			$maxDistance = (int)config('settings.list.search_distance_max', 500);
			$distanceSliderStep = 1;
		@endphp
		<script>
			$(document).ready(function ()
			{
				let minDistance = {{ $minDistance }};
				let maxDistance = {{ $maxDistance }};
				let distanceSliderStep = {{ $distanceSliderStep }};
				
				{{-- Price --}}
				let distance = {{ $qDistance }};
				
				let distanceRangeSliderEl = document.getElementById('distanceRangeSlider');
				noUiSlider.create(distanceRangeSliderEl, {
					connect: true,
					start: distance,
					step: distanceSliderStep,
					keyboardSupport: true,     			 /* Default true */
					keyboardDefaultStep: 5,    			 /* Default 10 */
					keyboardPageMultiplier: 5, 			 /* Default 5 */
					keyboardMultiplier: distanceSliderStep, /* Default 1 */
					range: {
						'min': minDistance,
						'max': maxDistance
					}
				});
				
				let distanceEl = document.getElementById('distance');
				
				distanceRangeSliderEl.noUiSlider.on('update', function (value) {
					distanceEl.value = Math.round(value);
				});
				distanceEl.addEventListener('change', function () {
					distanceRangeSliderEl.noUiSlider.set(this.value);
				});
			});
		</script>
	@endif
@endsection