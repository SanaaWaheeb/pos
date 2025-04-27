@extends('storefront.layout.theme4')
@section('page-title')
    {{__('Shipping')}}
@endsection
@php
     $productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp
@section('content')
<div class="wrapper">
    <section class="cart-section padding-bottom padding-top">
        <div class="container">
            <div class="row align-items-center cart-head">
                <div class="col-md-12 col-12">
                    <div class="cart-title">
                        <h2>{{ __('Hotel Booking') }}</h2>
                        <p style="margin-top: 10px"> {{ __('Fill the form below so we can send you the orders invoice.') }}</p>
                    </div>
                </div>
                {{-- <div class="col-lg-9 col-md-12 col-12 justify-content-end">
                    <div class="cart-btns">
                        <a href="{{ route('store.cart', $store->slug) }}">1 - {{ __('My Cart') }}</a>
                        <a href="{{ route('user-address.useraddress', $store->slug) }}" class="active-btn">2 -{{ __('Customer') }}</a>
                        <a href="{{ route('store-payment.payment', $store->slug) }}">3 - {{ __('Payment') }}</a>
                    </div>
                </div> --}}

            </div>
            {{ Form::model($cust_details, ['route' => ['store.customer', $store->slug], 'method' => 'POST']) }}
                <div class="row">
                    <!-- Booking Information -->
                    <div class="col-lg-8 col-12">
                        <div class="customer-info">
                            <h5>{{ __('Booking Information') }}</h5>
                        </div>

                        <div class="row">
                            <!-- <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('number_of_nights',__('Number of Nights'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('number_of_nights',old('Number of Nights'),array('class'=>'form-control','placeholder'=>__('Enter Number of Nights'),'required'=>'required'))}}
                                </div>
                            </div> -->
                            <!-- <div class="col-12">
                                <div class="form-group">
                                    {{ Form::label('date_range', __('Choose Dates'), ['class' => 'form-label']) }} <span style="color:red">*</span>
                                    <input type="text" id="date-range" name="date_range" class="form-control" placeholder="{{__('Check-in - Check-out')}}" required>
                                    <input type="hidden" id="check-in-date" name="check_in_date">
                                    <input type="hidden" id="check-out-date" name="check_out_date">
                                    <input type="hidden" id="number_of_nights" name="number_of_nights">
                                </div>
                            </div> -->

                            <div class="col-12">
                                <div class="form-group">
                                    {{ Form::label('date_range', __('Choose Dates'), ['class' => 'form-label']) }} <span style="color:red">*</span>
                                    <input type="hidden" id="check-in-date" name="check_in_date">
                                    <input type="hidden" id="check-out-date" name="check_out_date">
                                    <input type="hidden" id="number_of_nights" name="number_of_nights">
                                        @csrf
                                        @include('components.price-calendar', ['readOnly' => true])
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Summary -->
                    <div class="col-lg-4 col-12">
                        <div class="mini-cart" id="card-summary" style="margin: 40px 0px">
                            <div class="mini-cart-header">
                                <h4>{{ __('Summary') }}</h4>
                            </div>
                            <div id="cart-body" class="mini-cart-has-item">
                                <div class="mini-cart-body">
                                    @if (!empty($products))
                                        @php
                                            $total = 0;
                                            $sub_tax = 0;
                                            $sub_total = 0;
                                        @endphp
                                        @foreach ($products as $product)
                                            @if (isset($product['variant_id']) && !empty($product['variant_id']))
                                                <div class="mini-cart-item">
                                                    <div class="mini-cart-image">
                                                        <a href="#">
                                                            <img src="{{$productImg .$product['image']}}" alt="img">
                                                        </a>
                                                    </div>
                                                    <div class="mini-cart-details">
                                                        <p class="mini-cart-title">
                                                            <a href="#">{{$product['product_name'].' - ( ' . $product['variant_name'] .' ) '}}</a>
                                                        </p>
                                                        @php
                                                            $total_tax=0;
                                                        @endphp
                                                        <!-- <div class="pvarprice d-flex align-items-center justify-content-between">
                                                            <div class="price">
                                                                <small>
                                                                    {{$product['quantity']}} × {{\App\Models\Utility::priceFormat($product['variant_price'])}}
                                                                    @if(!empty($product['tax']))
                                                                        +
                                                                        @foreach($product['tax'] as $tax)
                                                                            @php
                                                                                $sub_tax = ($product['variant_price'] * $product['quantity'] * $tax['tax']) / 100;
                                                                                $total_tax += $sub_tax;
                                                                            @endphp
    
                                                                            {{\App\Models\Utility::priceFormat($sub_tax).' ('.$tax['tax_name'].' '.($tax['tax']).'%)'}}
                                                                        @endforeach
                                                                    @endif
                                                                </small>
                                                                @php
                                                                    $totalprice = $product['variant_price'] * $product['quantity'] + $total_tax;
                                                                    $subtotal = $product['variant_price'] * $product['quantity'];
                                                                    $sub_total += $subtotal;
                                                                @endphp
                                                            </div>
                                                            <a class="remove_item" style="margin: 10px 0 5px 0" data-price="{{ $totalprice }}">
                                                                {{\App\Models\Utility::priceFormat($totalprice)}} / {{__('Night')}}
                                                            </a>
                                                        </div> -->
                                                    </div>
                                                </div>
                                                @php
                                                    $total += $totalprice;
                                                @endphp
                                            @else
                                                <div class="mini-cart-item">
                                                    <div class="mini-cart-image">
                                                        <a href="#">
                                                            <img src="{{$productImg .$product['image']}}" alt="img">
                                                        </a>
                                                    </div>
                                                    <div class="mini-cart-details">
                                                        <p class="mini-cart-title">
                                                            <a href="#">{{$product['product_name']}}</a>
                                                        </p>
                                                        @php
                                                            $total_tax=0;
                                                        @endphp
                                                        <!-- <div class="pvarprice d-flex align-items-center justify-content-between">
                                                            <div class="price">
                                                                <small>
                                                                    {{$product['quantity']}} × {{\App\Models\Utility::priceFormat($product['price'])}}
                                                                    @if(!empty($product['tax']))
                                                                        +
                                                                        @foreach($product['tax'] as $tax)
                                                                            @php
                                                                                $sub_tax = ($product['price'] * $product['quantity'] * $tax['tax']) / 100;
                                                                                $total_tax += $sub_tax;
                                                                            @endphp
        
                                                                            {{\App\Models\Utility::priceFormat($sub_tax).' ('.$tax['tax_name'].' '.($tax['tax']).'%)'}}
                                                                        @endforeach
                                                                    @endif
                                                                </small>
                                                                @php
                                                                    $totalprice = $product['price'] * $product['quantity'] + $total_tax;
                                                                    $subtotal = $product['price'] * $product['quantity'];
                                                                    $sub_total += $subtotal;
                                                                @endphp
                                                            </div>
                                                            <a class="remove_item" href="#" style="margin: 10px 0 5px 0" data-price="{{ $totalprice }}">
                                                                {{\App\Models\Utility::priceFormat($totalprice)}} / {{__('Night')}}
                                                            </a>
                                                            @php
                                                            $total += $totalprice;
                                                            @endphp
                                                        </div> -->
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mini-cart-footer">
                                    {{-- <div class="u-save d-flex justify-content-between">
                                        <div class="cpn-lbl">{{ __('Subtotal') }}</div>
                                        <div class="cpn-price">{{\App\Models\Utility::priceFormat( !empty($sub_total)?$sub_total:'0')}}</div>
                                    </div> --}}
                                    {{-- <div class="u-save d-flex justify-content-between">
                                        <div class="cpn-lbl">{{ __('Coupon') }}</div>
                                        <div class="cpn-price dicount_price">{{\App\Models\Utility::priceFormat(0)}}</div>
                                    </div> --}}
                                    {{-- @if($store->enable_shipping == "on")
                                        <div class="u-save d-flex justify-content-between">
                                            <div class="cpn-lbl">{{__('Shipping Price')}} </div>
                                            <div class="cpn-price shipping_price" data-value=""></div>
                                        </div>
                                    @endif --}}
                                    @foreach($taxArr['tax'] as $k=>$tax)
                                        <div class="u-save d-flex justify-content-between">
                                            @php
                                                $rate = $taxArr['rate'][$k];
                                            @endphp
                                            <div class="cpn-lbl">{{$tax}}</div>
                                            <div class="cpn-price">{{\App\Models\Utility::priceFormat($rate)}}</div>
                                        </div>
                                    @endforeach
                                    <!-- Display service per night -->
                                     <ul class="cart-summery">
                                        <div class="u-save d-flex justify-content-between">
                                            <div class="cpn-lbl">{{ __('Check-in Date') }}</div>
                                            <div id="check-in"></div>
                                        </div>
                                        <div class="u-save d-flex justify-content-between">
                                            <div class="cpn-lbl">{{ __('Check-out Date') }}</div>
                                            <div id="check-out"></div>
                                        </div>
                                        <div class="u-save d-flex justify-content-between">
                                            <div class="cpn-lbl">{{__('Number of Nights')}}</div>
                                            <div id="num-nights">{{__('Night')}}</div>
                                        </div>
                                     </ul>
                                    <div
                                        class="mini-cart-footer-total-row d-flex align-items-center justify-content-between">
                                        <div class="mini-total-lbl">
                                            {{__('Total')}}
                                        </div>
                                        <div class="mini-total-price final_total_price" id="total_value" data-value="666">
                                            <input type="hidden" class="product_total" value="{{$total}}">
                                            <input type="hidden" class="total_pay_price" value="{{App\Models\Utility::priceFormat($total)}}">
                                            <input type="hidden" name="total" id="total-booking-price" value="{{ $total }}">
                                            <span class="pro_total_price" data-value="{{\App\Models\Utility::priceFormat(!empty($total)?$total:0)}}"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Billing Information -->
                    <div class="col-lg-8 col-12">
                        <div class="customer-info">
                            <h5>{{ __('Customer Information') }}</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('name',__('First Name'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('name',old('name'),array('class'=>'form-control','placeholder'=>__('Enter Your First Name'),'required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('last_name',__('Last Name'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('last_name',old('last_name'),array('class'=>'form-control','placeholder'=>__('Enter Your Last Name'),'required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('phone',__('Phone'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('phone',old('phone'),array('class'=>'form-control','placeholder'=>'(+966) 560747785','required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('email',__('Email'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::email('email',(Utility::CustomerAuthCheck($store->slug) ? Auth::guard('customers')->user()->email : ''),array('class'=>'form-control','placeholder'=>__('Enter Your Email Address'),'required'=>'required'))}}
                                </div>
                            </div>                            
                            @if(!empty($store_payment_setting['custom_field_title_1']))
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('custom_field_title_1',$store_payment_setting['custom_field_title_1'],array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('custom_field_title_1',old('custom_field_title_1'),array('class'=>'form-control','placeholder'=>'Enter '.$store_payment_setting['custom_field_title_1'],'required'=>'required'))}}
                                </div>
                            </div>
                            @endif
                            @if(!empty($store_payment_setting['custom_field_title_2']))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{Form::label('custom_field_title_2',$store_payment_setting['custom_field_title_2'],array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                        {{Form::text('custom_field_title_2',old('custom_field_title_2'),array('class'=>'form-control','placeholder'=>'Enter '.$store_payment_setting['custom_field_title_1'],'required'=>'required'))}}
                                    </div>
                                </div>
                            @endif
                            @if(!empty($store_payment_setting['custom_field_title_3']))
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            {{Form::label('custom_field_title_3',$store_payment_setting['custom_field_title_3'],array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                            {{Form::text('custom_field_title_3',old('custom_field_title_3'),array('class'=>'form-control','placeholder'=>'Enter '.$store_payment_setting['custom_field_title_1'],'required'=>'required'))}}
                                        </div>
                                    </div>
                            @endif
                            
                            @if(!empty($store_payment_setting['custom_field_title_4']))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{Form::label('custom_field_title_4',$store_payment_setting['custom_field_title_4'],array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                        {{Form::text('custom_field_title_4',old('custom_field_title_4'),array('class'=>'form-control','placeholder'=>'Enter '.$store_payment_setting['custom_field_title_1'],'required'=>'required'))}}
                                    </div>
                                </div>
                            @endif
                            
                            {{-- <div class="col-md-12 col-12">
                                <div class="form-group">
                                    {{Form::label('billingaddress',__('Address'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('billing_address',old('billing_address'),array('class'=>'form-control','placeholder'=>__('Billing Address'),'required'=>'required'))}}
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-6 col-12">
                                <div class="form-group focused">
                                    {{Form::label('billing_country',__('Country'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    <select name="billing_country" id="" class="form-control change_country" required>
                                        <option value="">{{ __('Select Country') }}</option>
                                        @foreach($countries as $key => $value)
                                            <option value="{{ $key }}">{{ $key }}</option>
                                        @endforeach   
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('billing_city',__('City'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    <select name="billing_city" id="city" class="form-control" required>  
                                        <option value="">{{ __('select city') }}</option>
                                    </select>  
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('billing_postalcode',__('Postal Code'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('billing_postalcode',old('billing_postalcode'),array('class'=>'form-control','placeholder'=>__('Billing Postal Code'),'required'=>'required'))}}
                                </div>
                            </div> --}}
                            {{-- @if($store->enable_shipping == "on" && $shippings->count() > 0)
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{Form::label('location_id',__('Location'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                        {{ Form::select('location_id', $locations, null,array('class' => 'form-control change_location','required'=>'required')) }}
                                    </div>
                                </div>
                            @endif --}}
{{-- 
                            <div class="col-md-12 col-12">
                                <div class="row align-items-center">
                                    <div class="col-md-6 col-12">
                                        <div class="customer-info">
                                            <h5>{{__('Shipping informations')}}</h5>
                                            <p>{{__('Fill the form below so we can send you the orders invoice.')}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="addres-btn">
                                            <a class="cart-btn" onclick="billing_data()" id="billing_data" data-toggle="tooltip" data-placement="top" title="Same As Billing Address">
                                                {{__('Copy Address')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-12 col-12">
                                <div class="form-group">
                                    {{Form::label('shipping_address',__('Address'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_address',old('shipping_address'),array('class'=>'form-control','placeholder'=>__('Shipping Address')))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('shipping_country',__('Country'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_country',old('shipping_country'),array('class'=>'form-control','placeholder'=>__('Shipping Country')))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('shipping_city',__('City'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_city',old('shipping_city'),array('class'=>'form-control','placeholder'=>__('Shipping City')))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('shipping_postalcode',__('Postal Code'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_postalcode',old('shipping_postalcode'),array('class'=>'form-control','placeholder'=>__('Shipping Postal Code')))}}
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="addres-btn">
                                    <a href="{{route('store.slug',$store->slug)}}" class="cart-btn">{{__('Return to shop')}}</a>
                                    <button type="submit" class="cart-btn btn">{{__('Next step')}}</button>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                   
                    
                    <div class="col-lg-4 col-12">
                        {{-- <div class="shiping-type">
                            <h5>{{__('Select Shipping')}}</h5>
                            <div class="radio-group" id="shipping_location_content">
                            </div>
                        </div> --}}
                        {{-- <div class="coupon-form">
                            <div class="coupon-header">
                                <h4>{{__('Coupon')}}</h4>
                            </div>
                            <div class="coupon-body">
                                <form action="">
                                    <div class="input-wrapper">
                                        <input type="text" id="stripe_coupon" name="coupon" class="coupon hidd_val" placeholder="{{ __('Enter Coupon Code') }}">
                                        <input type="hidden" name="coupon" class="hidden_coupon" value="">
                                    </div>
                                    <div class="btn-wrapper apply-stripe-btn-coupon">
                                        <button type="submit" class="btn apply-coupon">{{ __('Apply') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div> --}}                     
                    </div>
                    <div class="col-md-12 col-12"  style="margin-top: 80px">
                        <div class="pagination-btn d-flex align-items-center justify-content-center " style="width:100% ">
                            
                            <button type="submit" class="next-btn btn">{{__('Proceed to Checkout')}} <i class="fas fa-shopping-basket"></i></button>
                            
                            {{-- <a href="{{route('store.slug',$store->slug)}}" class="btn back-btn">{{__('Return to shop')}}</a> --}}
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </section>
</div>
@endsection
@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    
    <script>
        function billing_data() {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        }

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form'); // adjust selector if your form has ID or class

            form.addEventListener('submit', function(e) {
                const checkIn = document.getElementById('check-in-date').value;
                const checkOut = document.getElementById('check-out-date').value;
                const nights = document.getElementById('number_of_nights').value;

                if (!checkIn || !checkOut || !nights || nights <= 0) {
                    e.preventDefault(); // 🛑 Stop form submission
                    alert('Please select a valid date range first!');
                }
            });
        });

        // Handle select check-in and check-out dates
        // $(document).ready(function() {
        //     $('#date-range').daterangepicker({
        //         minDate: moment().format('YYYY-MM-DD'),
        //         locale: {
        //             format: 'YYYY-MM-DD',
        //             cancelLabel: 'Clear',
        //         },
        //         singleDatePicker: false,
        //         alwaysShowCalendars: true, 
        //         opens: 'center',
        //         showCustomRangeLabel: false, 
        //         linkedCalendars: false, // Ensures only one month is shown
        //         autoUpdateInput: false
        //     });

        //     $('#date-range').on('apply.daterangepicker', function(ev, picker) {
        //         let checkInDate = picker.startDate.format('YYYY-MM-DD');
        //         let checkOutDate = picker.endDate.format('YYYY-MM-DD');
        //         let nights = picker.endDate.diff(picker.startDate, 'days');

        //         // Prevent applying if no check-out date is selected
        //         if (nights < 1) {
        //             show_toastr('Error', "{{ __('Please select a valid check-out date') }}", 'error');
        //             return false; // Stop execution
        //         }

        //         // Update input values
        //         $(this).val(checkInDate + ' - ' + checkOutDate);
        //         $('#check-in-date').val(checkInDate);
        //         $('#check-out-date').val(checkOutDate);
        //         $('#number_of_nights').val(nights);
        //         $('#num-nights').text(nights + ' ' + (nights > 1 ? "{{ __('Nights') }}" : "{{ __('Night') }}"));

        //         // Get prices from .remove_item elements
        //         let removeItems = document.querySelectorAll(".remove_item");
        //         let prices = [];
        //         removeItems.forEach(function (item) {
        //             prices.push(item.dataset.price);
        //         });

        //         // Send AJAX request to update total price
        //         $.ajax({
        //             url: "{{ route('payment.total_booking') }}",
        //             data: {
        //                 "_token": $('meta[name="csrf-token"]').attr('content'),
        //                 nights,
        //                 prices
        //             },
        //             method: 'POST',
        //             dataType: 'json',
        //             success: function (data) {
        //                 $('.pro_total_price').html(data.total_price);
        //                 $('#total-booking-price').val(data.total_price); // Update hidden input field
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error("Error:", error);
        //             }
        //         });
        //     });

        //     $('#date-range').on('cancel.daterangepicker', function(ev, picker) {
        //         $(this).val('');
        //         $('#check-in-date').val('');
        //         $('#check-out-date').val('');
        //         $('#number_of_nights').val('');
        //     });
        // });

        // Triger changes in number of nights input field (old code)
        // document.addEventListener("DOMContentLoaded", function() {
        //     const price = "{{ $total }}";
        //     var nightsInput = document.querySelector('[name="number_of_nights"]');

        //     nightsInput.addEventListener("input", function() {
        //         var nights = parseInt(nightsInput.value) || 1;
        //         var updatedTotal = nights * price;
                
        //         // Update displayed number of nights
        //         const numNightsElement = document.querySelector('.pvarprice .price small');
        //         if (numNightsElement) {
        //             const nightText = "{{ __('Night') }}";
        //             const nightsText = "{{ __('Nights') }}";
        //             numNightsElement.innerHTML = `${nights > 1 ? `${nights} ${nightsText}` : nightText}`;
        //             // Update hidden input field
        //             $('#total-booking-price').val(updatedTotal);
        //         }

        //         // Update displayed total price
        //         $.ajax({
        //             url: "{{ route('payment.total_booking') }}",
        //             data: {
        //                 "_token": $('meta[name="csrf-token"]').attr('content'),
        //                 nights,
        //                 price
        //             },
        //             method: 'POST',
        //             dataType: 'json',

        //             success: function (data) {
        //                 $('.pro_total_price').html(data.total_price);
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error("Error:", error);
        //             }
        //         });
        //     });
        // });
        
        // function getTotal(shipping_id) {
        //     var pro_total_price = $('.pro_total_price').attr('data-value');
        //     if (shipping_id == undefined) {
        //         $('.shipping_price_add').hide();
        //         return false
        //     } else {
        //         $('.shipping_price_add').show();
        //     }

        //     $.ajax({
        //         url: '{{ route('user.shipping', [$store->slug,'_shipping'])}}'.replace('_shipping', shipping_id),
        //         data: {
        //             "pro_total_price": pro_total_price,
        //             "_token": "{{ csrf_token() }}",
        //         },
        //         method: 'POST',
        //         context: this,
        //         dataType: 'json',

        //         success: function (data) {
        //             var price = data.price + pro_total_price;
        //             $('.shipping_price').html(data.price);
        //             $('.shipping_price').attr('data-value', data.price);
        //             $('.pro_total_price').html(data.total_price);
        //         }
        //     });
        // }
    </script>
@endpush
