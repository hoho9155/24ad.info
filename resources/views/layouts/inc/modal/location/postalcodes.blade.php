@php
	$countryCode ??= config('country.code');
	$countryCode = strtolower($countryCode);

	$languageCode ??= config('app.locale');
	
@endphp

@if (!empty($cities))

	<div class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-1 row-cols-1">
		@foreach($cities as $city)
			@php
				$fullCityName = data_get($city, 'value');
				$displayedCityName = str($fullCityName)->limit(40);
			@endphp
			<div class="col mb-1 list-link list-unstyled">
				<a href=""
				   data-bs-toggle="tooltip"
				   data-bs-custom-class="modal-tooltip"
				   title="{{ $fullCityName }}"
				   class="is-city"
				   data-id="{{ data_get($city, 'data') }}"
				   data-name="{{ $fullCityName }}"
				>
					{{ $displayedCityName }}
				</a>
			</div>
		@endforeach
	</div>
@else
	<div class="row">
		<div class="col-12">
			{{ t('no_cities_found', [], 'global', $languageCode) }}
		</div>
	</div>
@endif