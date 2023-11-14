@if (config('settings.currencyexchange.activation'))
	@if (isset($currencies) && !empty($currencies))
		<li class="nav-item dropdown no-arrow open-on-hover">
			<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown" data-target="#currenciesDropdownMenu">
				<span>{!! config('selectedCurrency.symbol') !!} {{ config('selectedCurrency.code') }}</span>
				<i class="fas fa-chevron-down hidden-sm"></i>
			</a>
			<ul id="currenciesDropdownMenu" class="dropdown-menu user-menu">
				@foreach($currencies as $iCurr)
					<li class="{!! ($iCurr->get('code') == config('selectedCurrency.code')) ? 'dropdown-item active' : 'dropdown-item' !!}">
						<a href="{!! qsUrl(request()->path(), array_merge(request()->except(['curr']), ['curr' => $iCurr->get('code')]), null, false) !!}">
							{!! $iCurr->get('symbol') ? $iCurr->get('symbol') : '-' !!} {{ $iCurr->get('code') }}
						</a>
					</li>
				@endforeach
			</ul>
		</li>
	@endif
@endif
