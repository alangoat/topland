<div class="form-group mb-3">
    <label class="control-label">{{ __('Number of properties per page') }}</label>
    {!! Form::customSelect('per_page', RealEstateHelper::getPropertiesPerPageList(), Arr::get($attributes, 'per_page', 12)) !!}
</div>
