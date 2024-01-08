@extends('layouts.edit-form', [
    'item' => $item,
    'updateText' => null,
    'createText' => null,
    'requestNotes' => trans('hardware/form.request_notes'),
    'helpPosition'  => 'right',
    'helpText' => trans('help.companies'),
    'formAction' => route('account/request-asset', $item->id)]),
@section('inputFields')
    @include('partials.forms.edit.license-select',['fieldname' =>'license_request', 'translated_name' => 'License Request', 'multiple' => 'multiple'])
    @livewire('requestable-notes',[ 'item' => $item, 'accessory_request' => $accessory_request, 'license_request' => $license_request])
@stop

