@extends('layouts.edit-form', [
    'item' => $item,
    'updateText' => null,
    'createText' => null,
    'requestNotes' => trans('hardware/form.request_notes'),
    'helpPosition'  => 'right',
    'helpText' => trans('help.companies'),
    'formAction' => route('account/request-asset', $item->id)]),
@section('inputFields')

@stop

@section('livewire')
@livewire('requestable-notes')
@stop