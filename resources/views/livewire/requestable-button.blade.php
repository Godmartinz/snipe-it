@extends('layouts.edit-form', [
    'item' => $requested_asset,
    'createText' => trans('admin/companies/table.create') ,
    'updateText' => trans('admin/companies/table.update'),
    'helpPosition'  => 'right',
    'helpText' => trans('help.companies'),])

<div>
@section('inputFields')
    @include ('partials.forms.edit.license-select', ['translated_name' => trans('general.licenses_available'),'fieldname' => 'requested_licenses[]'] )
    @include ('partials.forms.edit.accessory-select', ['translated_name' => trans('general.accessories'), 'fieldname' => 'requested_accessories[]'] )
    {{--@include ('partials.forms.edit.notes', ['item' => $request])--}}
@stop
</div>
