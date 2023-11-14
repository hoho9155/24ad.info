@php
    $twocheckoutIntegration = config('payment.twocheckout.integration');
@endphp
@if (in_array($twocheckoutIntegration, ['redirect', 'authorize']))
    @if (view()->exists('payment::twocheckout.' . $twocheckoutIntegration))
        @include('payment::twocheckout.' . $twocheckoutIntegration)
    @endif
@endif