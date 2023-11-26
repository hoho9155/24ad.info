@if (config('settings.currencyexchange.activation'))
	@if (isset($currencies) && !empty($currencies))
		<li class="nav-item dropdown no-arrow open-on-hover">
			<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown" data-target="#currenciesDropdownMenu" style="margin-top: 3px;">
				<span>
				    <span class="currency-icon">{!! config('selectedCurrency.symbol') !!} </span>
				    <span class="currency-code">{{ config('selectedCurrency.code') }}</span>
				</span>
				<i class="bi bi-chevron-down"></i>
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
