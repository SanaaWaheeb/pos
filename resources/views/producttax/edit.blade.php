{{ Form::model($productTax, ['route' => ['product_tax.update', $productTax->id], 'method' => 'PUT', 'class'=>'needs-validation', 'novalidate']) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('tax_name', __('Tax Name'),['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Account Type'),'required'=>'required']) }}
            @error('tax_name')
                <span class="invalid-tax_name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('rate', __('Rate').__(' (%)'),['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::number('rate', null, ['class' => 'form-control', 'placeholder' => __('Enter Account Type'),'min'=>'0','required'=>'required']) }}
            @error('rate')
                <span class="invalid-rate" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="form-group col-12 d-flex justify-content-end col-form-label">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
