@extends('layouts.admin')
@section('page-title')
    {{ __('Product') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('product.index') }}">{{ __('Product') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
@endsection
@php
    $plan = \App\Models\Plan::find(\Auth::user()->plan);
@endphp
@section('action-btn')
    <div class="pr-2">
        @if($plan->enable_chatgpt == 'on')
            <a href="#" class="btn btn-primary me-2 mt-2" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['products']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
            </a>
        @endif
        <a href="{{ route('product.index') }}" class="btn btn-light-secondary me-2 mt-2"> <i data-feather="x-circle"
                class="me-2"></i>{{ __('Cancel') }}</a>
        <a href="#" type="submit" id="submit-all" class="btn btn-primary mt-2"> <i data-feather="check-circle"
                class="me-2"></i>{{ __('Save') }}</a>
    </div>
@endsection
@section('filter')
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
@endpush
@php
    $is_cover_image = \App\Models\Utility::get_file('uploads/is_cover_image/');
    $productimage = \App\Models\Utility::get_file('uploads/product_image/');
    
@endphp
@push('script-page')
    <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        if ($(".pc-tinymce-2").length) {
            tinymce.init({
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }',
                menubar:false,
                statusbar: false,
            });
        }
    </script>
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
    <script>
        var Dropzones = function() {
            var e = $('[data-toggle="dropzone1"]'),
                t = $(".dz-preview");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            e.length && (Dropzone.autoDiscover = !1, e.each(function() {
                var e, a, n, o, i;
                e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                    url: "{{ route('products.update', $product->id) }}",
                    method: 'PUT',
                    headers: {
                        'x-csrf-token': CSRF_TOKEN,
                    },
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    previewsContainer: n.get(0),
                    previewTemplate: n.html(),
                    maxFiles: 10,
                    parallelUploads: 10,
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    acceptedFiles: a ? null : "image/*",
                    success: function(file, response) {
                        if (response.flag == "success") {
                            show_toastr('success', response.msg, 'success');
                            window.location.href = "{{ route('product.index') }}";
                        } else {
                            show_toastr('Error', response.msg, 'error');
                        }
                    },
                    error: function(file, response) {
                        // Dropzones.removeFile(file);
                        if (response.error) {
                            show_toastr('Error', response.error, 'error');
                        } else {
                            show_toastr('Error', response, 'error');
                        }
                    },
                    init: function() {
                        var myDropzone = this;

                        this.on("addedfile", function(e) {
                            !a && o && this.removeFile(o), o = e
                        })
                    }
                }, n.html(""), e.dropzone(i)
            }))
        }()

        $('#submit-all').on('click', function(e) {
            $('.product-submit-button').trigger('click');
        });
        $(document).on("submit", ".submit-product", function (e) {
        // $('#submit-all').on('click', function(e) {
            e.preventDefault();

            var form = $(this).parents('form');
            var variantNameEle = $('#variant_name');
            var variantOptionsEle = $('#variant_options');
            var isValid = true;
            if (variantNameEle.val() == '') {
                variantNameEle.focus();
                isValid = false;
            } else if (variantOptionsEle.val() == '') {
                variantOptionsEle.focus();
                isValid = false;
            }

            if (isValid) {
                var hiddenVariantOptions = $('#hiddenVariantOptions').val();
                
                $.ajax({
                    url: form.attr('action'),
                    datType: 'json',
                    data: {
                        variant_name: variantNameEle.val(),
                        variant_options: variantOptionsEle.val(),
                        hiddenVariantOptions: hiddenVariantOptions

                    },
                    success: function(data) {
                        if(data.hiddenVariantOptions == null){
                            $('#hiddenVariantOptions').val(hiddenVariantOptions)
                        }else{

                            $('#hiddenVariantOptions').val(data.hiddenVariantOptions);
                        }
                        $('.variant-table').html(data.varitantHTML);
                        $("#commonModal").modal('hide');
                        
                    }
                })
            }


            $('#cost').trigger('keyup');

            var fd = new FormData();
            var file = document.getElementById('is_cover_image').files[0];
            var attachmentfile = document.getElementById('attachment').files[0];
            var downloadable_prodcutfile = document.getElementById('downloadable_prodcut').files[0];

            if (file) {
                fd.append('is_cover_image', file);
            }
            if (attachmentfile) {
                fd.append('attachment', attachmentfile);
            }
            if (downloadable_prodcutfile) {
                fd.append('downloadable_prodcut', downloadable_prodcutfile);
            }

            var files = $('[data-toggle="dropzone1"]').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('multiple_files[' + key + ']', $('[data-toggle="dropzone1"]')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });
            // $('#description').val(tinyMCE.get("description").getContent())
            // $('#specification').val(tinyMCE.get("specification").getContent())
            // $('#detail').val(tinyMCE.get("detail").getContent())
            var other_data = $('#frmTarget').serializeArray();

            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });

            $.ajax({
                url: "{{ route('products.update', $product->id) }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.flag == "success") {
                        show_toastr('success', data.msg, 'success');
                        window.location.href = "{{ route('product.index') }}";
                    } else {
                        show_toastr('Error', data.msg, 'error');
                    }
                },
                error: function(data) {
                    if (data.error) {
                        show_toastr('Error', data.error, 'error');
                    } else {
                        show_toastr('Error', data, 'error');
                    }
                },
            });
            return false;
        });

        $(".deleteRecord").click(function() {

            var id = $(this).data("id");

            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: '{{ route('products.file.delete', '__product_id') }}'.replace('__product_id', id),
                type: 'DELETE',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function(data) {

                    if (data.success) {
                        show_toastr('success', data.success, 'success');
                        $('.product_Image[data-id="' + data.id + '"]').remove();
                    } else {
                        show_toastr('Error', data.error, 'error');
                    }
                }
            });
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        {{ Form::model($product, ['method' => 'POST', 'id' => 'frmTarget', 'enctype' => 'multipart/form-data', 'class'=>'submit-product needs-validation', 'novalidate']) }}
        <div class="col-sm-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class=" col-lg-6 col-md-6">
                            <h5>{{ __('Main Informations') }}</h5>
                            <div class="card shadow-none border border-primary">
                                <div class="card-body ">
                                    <div class="form-group">
                                        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
                                    </div>
                                   
                                    <div class="form-group">
                                        {{ Form::label('product_categorie', __('Product Categories'), ['class' => 'form-label']) }}
                                        {!! Form::select('product_categorie[]', $product_categorie, explode(',',$product->product_categorie), [
                                            'class' => 'form-control multi-select',
                                            'id' => 'choices-multiple',
                                            'multiple',
                                        ]) !!}
                                       
                                        @if (count($product_categorie) == 0)
                                            {{ __('Add product category') }}
                                            <a href="{{ route('product_categorie.index') }}">
                                                {{ __('Click here') }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="form-group proprice">
                                        <div class="row gy-4">
                                            <div class="col-md-6">
                                                {{ Form::label('price', __('Price'), ['class' => 'form-label']) }}<x-required></x-required>
                                                {{ Form::number('price', null, ['step' => 'any', 'class' => 'form-control']) }}
                                            </div>
                                            <div class="col-md-6">
                                                {{ Form::label('last_price', __('Last Price'), ['class' => 'form-label']) }}
                                                {{ Form::number('last_price', null, ['step' => 'any', 'class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('product_tax', __('Product Tax'), ['class' => 'form-label']) }}
                                        {{ Form::select('product_tax[]', $product_tax, explode(',',$product->product_tax), ['class' => 'form-control multi-select', 'id' => 'choices-multiple1', 'multiple']) }}
                                        @if (count($product_tax) == 0)
                                            {{ __('Add product tax') }}
                                            <a href="{{ route('product_tax.index') }}">
                                                {{ __('Click here') }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('SKU', __('SKU (Barcode)'), ['class' => 'form-label']) }}
                                        <x-required></x-required>
                                        <div class="d-flex align-items-center">
                                            {{ Form::text('SKU', null, ['class' => 'form-control me-2', 'placeholder' => __('Enter SKU'), 'id' => 'sku-input', 'pattern' => '[0-9]*', 'maxlength' => '12']) }}
                                            <button type="button" id="generate-barcode-btn" class="btn btn-sm btn-primary">{{ __('Generate') }}</button>
                                        </div>
                                    </div>
                                    <div id="barcode-container" class="mt-3" style="display: none;">
                                        <label class="form-label">{{ __('Generated Barcode:') }}</label>
                                        <div id="barcode-output" class="border p-3 text-center">
                                            <img id="barcode-image" src="" alt="{{ __('Barcode') }}">
                                            <div id="barcode-details" class="mt-2">
                                                <p><strong>{{ __('Product Name:') }}</strong> <span id="product-name"></span></p>
                                                <p><strong>{{ __('Price:') }}</strong> <span id="product-price"></span></p>
                                            </div>
                                        </div>
                                        <div class="mt-3 text-center">
                                            <button id="print-barcode-btn" class="btn btn-secondary btn-sm">{{ __('Print') }}</button>
                                            <button id="download-barcode-btn" class="btn btn-success btn-sm">{{ __('Download') }}</button>
                                        </div>
                                    </div>
                                    <canvas id="barcode-canvas" style="display: none;"></canvas>
                                    <div class="form-group proprice">
                                        {{ Form::label('quantity', __('Stock Quantity'), ['class' => 'form-label']) }}
                                        {{ Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => __('Enter Stock Quantity')]) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('expiry_date', __('Expiry Date'), ['class' => 'form-label']) }}
                                        {{ Form::date('expiry_date', null, ['class' => 'form-control', 'placeholder' => __('Enter Expiry Date'), 'min' => date('Y-m-d')]) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="attachment" class="form-label"
                                            onchange="loadImg()">{{ __('Attachment') }}</label>
                                        <input type="file" name="attachment" id="attachment" class="form-control"
                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                        <img id="blah" src="" width="20%" class="mt-2" />
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="downloadable_prodcut"
                                            class="form-label">{{ __('Downloadable Product') }}</label>
                                        <input type="file" name="downloadable_prodcut" id="downloadable_prodcut"
                                            class="form-control"
                                            onchange="document.getElementById('down_product').src = window.URL.createObjectURL(this.files[0])">
                                        <img id="down_product" src="" width="20%" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 col-md-6">
                            <h5>{{ __('Custom Field') }}</h5>
                            <div class="card shadow-none border border-primary">
                                <div class="card-body">
                                    <div class="form-group">
                                        {{ Form::label('custom_field_1', __('Custom Field'), ['class' => 'form-label']) }}
                                        {{ Form::text('custom_field_1', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Field')]) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('custom_value_1', __('Custom Value'), ['class' => 'form-label']) }}
                                        {{ Form::text('custom_value_1', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Value')]) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('custom_field_2', __('Custom Field'), ['class' => 'form-label']) }}
                                        {{ Form::text('custom_field_2', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Field')]) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('custom_value_2', __('Custom Value'), ['class' => 'form-label']) }}
                                        {{ Form::text('custom_value_2', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Value')]) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('custom_field_3', __('Custom Field'), ['class' => 'form-label']) }}
                                        {{ Form::text('custom_field_3', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Field')]) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('custom_value_3', __('Custom Value'), ['class' => 'form-label']) }}
                                        {{ Form::text('custom_value_3', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Value')]) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('custom_field_4', __('Custom Field'), ['class' => 'form-label']) }}
                                        {{ Form::text('custom_field_4', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Field')]) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('custom_value_4', __('Custom Value'), ['class' => 'form-label']) }}
                                        {{ Form::text('custom_value_4', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Value')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="card shadow-none border border-primary">
                                    <div class="card-body">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <div class="row gy-3">
                                                    @if (isset($product_variant_names))
                                                        <div class="col-lg-6">
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="enable_product_variant" id="enable_product_variant"  {{ $product['enable_product_variant']=='on' ? 'checked' : '' }}>   {{--  && !empty($productVariantArrays) --}}
                                                                    <input type="hidden" name="hiddenhidden" id="hiddenhidden" value="">
                                                                <label class="form-check-label"
                                                                    for="enable_product_variant">{{ __('Display Variants') }}</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-lg-6">
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="checkbox" name="product_display" class="form-check-input"
                                                                id="product_display" {{ $product->product_display == 'on' ? 'checked' : '' }}>
                                                            {{ Form::label('product_display', __('Product Display'), ['class' => 'form-check-label']) }}
                                                        </div>
                                                        @error('product_display')
                                                            <span class="invalid-product_display" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (isset($product_variant_names))
                            <div id="productVariant" class="col-md-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card shadow-none border border-primary my-3">
                                            <div class="card-header">
                                                <div class="row flex-grow-1">

                                                    <div class="col-md d-flex align-items-center">
                                                        <h5 class="card-header-title">{{ __('Product Variants') }}</h5>
                                                    </div>

                                                    <div class="col-md-auto">
                                                        @can('Edit Variants')
                                                            <button type="button" class="btn btn-sm btn-primary get-variants"
                                                                dataa-url="{{ route('product.variants.edit', $product->id) }}"><i
                                                                    class="fas fa-plus"></i>
                                                                {{ __('Add Variant') }}</button>
                                                        @endcan
                                                    </div>

                                                </div>


                                            </div>
                                            <div class="card-body">
                                                <div class="row form-group">
                                                    <div class="table-responsive">
                                                        <div class="card-body">
                                                            
                                                            <input type="hidden" id="hiddenVariantOptions"
                                                                name="hiddenVariantOptions"
                                                                value="{{ $product->variants_json }}">
                                                            <div class="variant-table">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr class="text-center">
                                                                            @if (isset($product_variant_names))
                                                                                @foreach ($product_variant_names as $variant)
                                                                                    <th><span>{{ ucwords($variant) }}</span>
                                                                                    </th>
                                                                                @endforeach
                                                                            @endif
                                                                            <th><span>{{ __('Price') }}</span></th>
                                                                            <th><span>{{ __('Quantity') }}</span></th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        @if (isset($productVariantArrays))
                                                                        @foreach ($productVariantArrays as $counter => $productVariant)
                                                                        {{-- @DD($productVariant['product_variants']['product_id']) --}}
                                                                                <tr data-id="{{ $productVariant['product_variants']['id'] }}">
                                                                                    @foreach (explode(' : ', $productVariant['product_variants']['name']) as $key => $values)
                                                                                        <td>
                                                                                            <input type="text"
                                                                                                name="variants[{{ $productVariant['product_variants']['id'] }}][variants][{{ $key }}][]"
                                                                                                autocomplete="off"
                                                                                                spellcheck="false"
                                                                                                class="form-control wid-100"
                                                                                                value="{{ $values }}" readonly>
                                                                                        </td>
                                                                                    @endforeach
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            name="variants[{{ $productVariant['product_variants']['id'] }}][price]"
                                                                                            autocomplete="off"
                                                                                            spellcheck="false"
                                                                                            placeholder="{{ __('Enter Price') }}"
                                                                                            class="form-control wid-100 vprice_{{ $counter }}"
                                                                                            value="{{ $productVariant['product_variants']['price'] }}" required>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            name="variants[{{ $productVariant['product_variants']['id'] }}][quantity]"
                                                                                            autocomplete="off"
                                                                                            spellcheck="false"
                                                                                            placeholder="{{ __('Enter Quantity') }}"
                                                                                            class="form-control wid-100 vquantity_{{ $counter }}"
                                                                                            value="{{ $productVariant['product_variants']['quantity'] }}" required>
                                                                                    </td>
                                                                                    <td
                                                                                        class="d-flex align-items-center mt-3 border-0">

                                                                                        <div class="d-flex">
                                                                                            @can('Delete Variants')
                                                                                                <a class="bs-pass-para align-items-center btn btn-sm btn-icon bg-light-secondary d-inline-flex"
                                                                                                    href="#"
                                                                                                    data-title="{{ __('Delete Lead') }}"
                                                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                                                    data-confirm-yes="delete-form-{{ $productVariant['product_variants']['id'] }}">
                                                                                                    <i class="ti ti-trash"></i>
                                                                                                </a>
                                                                                                @if ($loop->iteration == 1)
                                                                                                    <form action=""
                                                                                                        method="">
                                                                                                        @csrf
                                                                                                    </form>
                                                                                                @endif

                                                                                                {!! Form::open([
                                                                                                    'method' => 'DELETE',
                                                                                                    'route' => ['products.variant.delete', [$productVariant['product_variants']['id'],$productVariant['product_variants']['product_id']]],
                                                                                                    'id' => 'delete-form-' . $productVariant['product_variants']['id'],
                                                                                                ]) !!}
                                                                                                {!! Form::hidden('variant_options', $productVariant['product_variants']['name'], ['id' => 'invisible_id']) !!}
                                                                                                {!! Form::close() !!}
                                                                                            @endcan
                                                                                        </div>
                                                                                    </td>

                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <h5>{{ __('Product Image') }}</h5>
                    <div class="card shadow-none border border-primary">
                        <div class="card-body">
                            <div class="form-group">
                                {{ Form::label('sub_images', __('Upload Product Images'), ['class' => 'form-label']) }}
                                <div class="dropzone dropzone-multiple" data-toggle="dropzone1"
                                    data-dropzone-url="http://" data-dropzone-multiple>
                                    <div class="fallback">
                                        <div class="custom-file">
                                            {{-- <input type="file" class="custom-file-input" id="dropzone-1" name="file"
                                                multiple> --}}
                                                <input type="file" class="custom-file-input" id="dropzone-1" name="file" multiple>
                                            <label class="custom-file-label" for="customFileUpload">{{ __('Choose file') }}</label>
                                        </div>
                                    </div>
                                    <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="avatar">
                                                        <img class="rounded" src="" alt="Image placeholder"
                                                            data-dz-thumbnail>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                                    <p class="small text-muted mb-0" data-dz-size>
                                                    </p>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="dropdown-item" data-dz-remove>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group pt-3">
                                    <div class="row gy-3 gx-3">
                                        @foreach ($product_image as $file)
                                            <div class="col-sm-6 product_Image" data-id="{{ $file->id }}">
                                                <div class="position-relative p-2 border rounded border-primary overflow-hidden rounded">
                                                    <img src="{{ $productimage . $file->product_images }}" alt="" class="w-100">
                                                    <div class="position-absolute text-center top-50 end-0 start-0 pb-3">
                                                        <a href="{{ $productimage . $file->product_images }}" download="" data-original-title="{{ __('Download') }}" class="btn btn-sm btn-primary me-2"><i class="ti ti-download"></i></a>
                                                        <a class="btn btn-sm btn-danger deleteRecord" name="deleteRecord" data-id="{{ $file->id }}"><i class="ti ti-trash"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="is_cover_image" class="col-form-label">{{ __('Upload Cover Image') }}</label>
                                <input type="file" name="is_cover_image" id="is_cover_image"
                                    class="form-control"
                                    onchange="document.getElementById('coverImg').src = window.URL.createObjectURL(this.files[0])"
                                    multiple>
                                <img id="coverImg"src="" width="20%" class="mt-2" />
                            </div>
                            @if(!empty($product->is_cover))
                                <div class="form-group">
                                    <div class="row gy-3 gx-3">
                                        <div class="col-sm-6">
                                            <div class="position-relative p-2 border rounded border-primary overflow-hidden rounded">
                                                <img src="{{ $is_cover_image . $product->is_cover }}" alt="" class="w-100">
                                                <div class="position-absolute text-center top-50 end-0 start-0 pb-3">
                                                    <a href="{{ $is_cover_image . $product->is_cover }}" class="btn btn-sm btn-primary me-2"><i class="ti ti-download"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <h5>{{ __('About product') }}</h5>
                    <div class="card shadow-none border border-primary">
                        <div class="card-body">
                            <div class="form-group">
                                {{ Form::label('description', __('Product Description'), ['class' => 'form-label']) }}
                                {{ Form::textarea('description', !empty($product->description) ? $product->description : '', ['class' => 'form-control summernote-simple', 'rows' => 1, 'placeholder' => __('Product Description'), 'id' => 'description']) }} {{-- pc-tinymce-2 --}}
                            </div>
                            <div class="form-group">
                                {{ Form::label('specification', __('Product Specification'), ['class' => 'form-label']) }}
                                {{ Form::textarea('specification', !empty($product->specification) ? $product->specification : '', ['class' => 'form-control summernote-simple', 'rows' => 1, 'placeholder' => __('Product Specification'), 'id' => 'specification']) }} {{-- pc-tinymce-2 --}}
                            </div>
                            <div class="form-group">
                                {{ Form::label('detail', __('Product Details'), ['class' => 'form-label']) }}
                                {{ Form::textarea('detail', !empty($product->detail) ? $product->detail : '', ['class' => 'form-control summernote-simple', 'rows' => 1, 'placeholder' => __('Product Details'), 'id' => 'detail']) }} {{-- pc-tinymce-2 --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" value="{{__('Update')}}" class="product-submit-button d-none btn btn-primary ms-2">
        </div>
        {{ Form::close() }}
    </div>
@endsection
@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const generateButton = document.getElementById('generate-barcode-btn');
    const skuInput = document.getElementById('sku-input');
    const barcodeContainer = document.getElementById('barcode-container');
    const productNameDisplay = document.getElementById('product-name');
    const productPriceDisplay = document.getElementById('product-price');
    const printButton = document.getElementById('print-barcode-btn');
    const downloadButton = document.getElementById('download-barcode-btn');
    
    const productNameInput = document.querySelector('input[name="name"]');
    const productPriceInput = document.querySelector('input[name="price"]');
    
    const JsBarcode = window.JsBarcode; // Ensure JsBarcode is available

    // Function to generate the barcode
    function generateBarcode(sku) {
        const barcodeImage = document.getElementById("barcode-image");

        // Generate barcode with JsBarcode
        JsBarcode(barcodeImage, sku, {
            format: "CODE128",
            width: 2,
            height: 50,
            displayValue: true,
            fontSize: 18
        });

        // Get product details
        const productName = productNameInput.value.trim() || 'N/A';
        const productPrice = productPriceInput.value.trim() || 'N/A';

        // Update the display
        productNameDisplay.textContent = productName;
        productPriceDisplay.textContent = productPrice;

        // Show the barcode container
        barcodeContainer.style.display = 'block';

        // Prepare Canvas for Download
        const canvas = document.getElementById("barcode-canvas");
        const ctx = canvas.getContext("2d");

        // Set Canvas Dimensions
        canvas.width = 400;
        canvas.height = 200;

        // Clear canvas before drawing
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Fill Background
        ctx.fillStyle = "#ffffff";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Add Product Name
        ctx.fillStyle = "#000000";
        ctx.font = "16px Arial";
        ctx.fillText("Name: " + productName, 10, 20);

        // Add Product Price
        ctx.fillText("Price: " + productPrice, 10, 50);

        // Wait for the barcode image to load before drawing it on canvas
        barcodeImage.onload = function () {
            ctx.drawImage(barcodeImage, 10, 70, 380, 100);
        };
    }

    // Generate random SKU when the button is clicked
    generateButton.addEventListener('click', function () {
        let sku = skuInput.value.trim();

        if (sku === "") {
            // Generate a random SKU if the input is empty
            sku = Math.floor(Math.random() * 1000000000000);
            skuInput.value = sku; // Populate the input with the generated SKU
        }

        generateBarcode(sku); // Generate the barcode
    });

    // Download the barcode as an image when the download button is clicked
    downloadButton.addEventListener("click", function () {
        const canvas = document.getElementById("barcode-canvas");

        // Trigger Download
        const link = document.createElement("a");
        link.download = "barcode.png";
        link.href = canvas.toDataURL("image/png");
        link.click();
    });

    // Event listener for the Print button
    printButton.addEventListener('click', function () {
        const barcodeImage = document.getElementById("barcode-image");
        const productName = productNameDisplay.textContent;
        const productPrice = productPriceDisplay.textContent;

        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Barcode</title></head><body>');
        printWindow.document.write('<img src="' + barcodeImage.src + '" />');
        printWindow.document.write('<p><strong>Product Name:</strong> ' + productName + '</p>');
        printWindow.document.write('<p><strong>Price:</strong> ' + productPrice + '</p>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
});
</script>

@endpush


