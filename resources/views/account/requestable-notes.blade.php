@extends('layouts.edit-form', [
    'item' => $item,
    'updateText' => null,
    'createText' => null,
    'requestNotes' => trans('hardware/form.request_notes'),
    'formAction' => route('account/request-asset', $item->id)]),
@section('inputFields')

    @livewire('requestable-notes',[ 'item' => $item])
@stop

