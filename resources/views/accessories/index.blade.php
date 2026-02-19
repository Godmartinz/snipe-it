@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('general.accessories') }}
@parent
@stop


{{-- Page content --}}
@section('content')
    <x-container>
        <x-box>
            <x-table.accessories name="accessories" :route="route('api.accessories.index')" />
        </x-box>
    </x-container>
@stop


@section('moar_scripts')
@include ('partials.bootstrap-table')
@stop
