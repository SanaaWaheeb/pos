@extends('storefront.layout.theme5')
@section('page-title')
    {{ __('Self Payment') }}
@endsection
@php
    $currency = $store->currency;
@endphp

@section('content')
<div class="wrapper d-flex direction-column" style="min-height: 100vh">
    <div class="justify-content-center align-items-center d-flex direction-column" style="gap: 2rem; padding: 2rem; text-align: center; flex: 0.5">
        <div class="d-flex direction-column" style="gap: 0.5rem">
            <h4 class="cart-title"> {{ __('The Total Price') }} </h4>
            <p> {{ __('Complete your purchase by entering the price') }} </p>
        </div>

        <div class="d-flex direction-column" style="width: 100%; max-width: 500px; gap: 1rem">
            <div class="d-flex align-items-center justify-content-center" style="gap: 0.5rem">
                <input
                    type="number"
                    id="priceInput"
                    class="form-control form-control-flush"
                    placeholder="{{ __('Enter here...') }}"
                    inputmode="decimal"
                    pattern="[0-9]*"
                    style="flex: 1"
                    autofocus
                />
                <span>{{ $currency }}</span>
            </div>

            <a href="#" id="confirmBtn" class="btn">{{ __('Confirm Order') }}</a>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    document.getElementById('confirmBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const price = document.getElementById('priceInput').value;
        if (price) {
            const slug = "{{ $store->slug }}";
            const url = `{{ url('payment-checkout') }}/${slug}/${price}`;
            window.location.href = url;
        } else {
            show_toastr('Error', "{{ __('Please enter a price') }}", 'error');
        }
    });
</script>
@endpush
