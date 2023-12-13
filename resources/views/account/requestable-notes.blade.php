@extends('layouts.edit-form', [
    'item' => $item,
    'updateText' => null,
    'createText' => null,
    'requestNotes' => trans('hardware/form.request_notes'),
    'helpPosition'  => 'right',
    'helpText' => trans('help.companies'),
    'formAction' => route('account/request-asset', $item->id)]),
@section('inputFields')
@include ('partials.forms.edit.license-select', ['translated_name' => trans('general.licenses_available'),'fieldname' => 'requested_licenses'] )
@include ('partials.forms.edit.accessory-select', ['translated_name' => trans('general.accessories'), 'fieldname' => 'requested_accessories'] )
@stop

@section('livewire')
@livewire('requestable-notes')
@stop