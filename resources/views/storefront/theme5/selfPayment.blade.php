@extends('storefront.layout.theme5')
@section('page-title')
    {{ __('Self Payment') }}
@endsection
@php
    $currency = $store->currency;
    $store_name = $store->name;
@endphp

@section('content')
<div class="wrapper d-flex direction-column justify-content-around" style="min-height: 90vh">
    <div id="self-payment" class="d-flex direction-column justify-content-between">
        <!-- Top white section -->
        <div class="top-bar">
            <div class="amount-display">
                <span id="displayAmount">0.00</span>
                <span class="currency"> {{ $currency }}</span>
              </div>
              
        </div>

        <!-- Red keypad section including confirm button -->
        <div class="keyboard-section">
            <div class="keypad">
                <button onclick="appendValue('1')">1</button>
                <button onclick="appendValue('2')">2</button>
                <button onclick="appendValue('3')">3</button>
                <button onclick="appendValue('4')">4</button>
                <button onclick="appendValue('5')">5</button>
                <button onclick="appendValue('6')">6</button>
                <button onclick="appendValue('7')">7</button>
                <button onclick="appendValue('8')">8</button>
                <button onclick="appendValue('9')">9</button>
                <button onclick="appendValue('.')">.</button>
                <button onclick="appendValue('0')">0</button>
                <button onclick="deleteLast()">⌫</button>
            </div>
                <div class="confirm-bg">
                    <div class="confirm-btn" onclick="confirmPayment()">{{ __('Confirm Order') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    let currentInput = "";

    function updateDisplay() {
        document.getElementById('displayAmount').textContent = currentInput || "0.00";
    }

    function appendValue(val) {
        if (val === '.' && currentInput.includes('.')) return;
        currentInput += val;
        updateDisplay();
    }

    function deleteLast() {
        currentInput = currentInput.slice(0, -1);
        updateDisplay();
    }

    function confirmPayment() {
        if (!currentInput || parseFloat(currentInput) <= 0) {
            show_toastr('Error', "{{ __('Please enter a valid price') }}", 'error');
            return;
        }

        const slug = "{{ $store->slug }}";
        const checkoutUrl = `{{ url('payment-checkout') }}/${slug}/${parseFloat(currentInput).toFixed(2)}`;

        $.ajax({
            url: checkoutUrl,
            type: 'GET',
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.redirect_url) {
                    // Redirect to the payment gateway
                    window.location.href = response.redirect_url;
                } else if (response.error) {
                    show_toastr('Error', response.error, 'error');
                }
            },
            error: function (xhr) {
                let errorMessage = 'Something went wrong. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                show_toastr('Error', errorMessage, 'error');
            }
        })     
    }

    // Prevent double-tap zoom on mobile
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function (event) {
    const now = new Date().getTime();
    if (now - lastTouchEnd <= 300) {
        event.preventDefault();
    }
    lastTouchEnd = now;
    }, false);
</script>
@endpush
