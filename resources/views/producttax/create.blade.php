{{ Form::open(['url' => 'product_tax', 'method' => 'post', 'class'=>'needs-validation', 'novalidate']) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('tax_name', __('Tax Name'),['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::text('tax_name', null, ['class' => 'form-control','placeholder' => __('Enter Tax Name'),'required' => 'required']) }}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('rate', __('Rate').__(' (%)'),['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::number('rate', null, ['class' => 'form-control','placeholder' => __('Enter Rate'),'min'=>'0','required' => 'required']) }}
        </div>
    </div>
    <div class="form-group col-12 d-flex justify-content-end col-form-label">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Save') }}" class="btn btn-primary ms-2">
    </div>
</div>
{{ Form::close() }}
