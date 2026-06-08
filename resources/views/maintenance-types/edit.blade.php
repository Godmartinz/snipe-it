@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @if ($item->id)
        {{ trans('admin/maintenance_types/general.update') }}
    @else
        {{ trans('admin/maintenance_types/general.create') }}
    @endif
    @parent
@stop

@section('header_right')
    <a href="{{ URL::previous() }}" class="btn btn-primary pull-right">
        {{ trans('general.back') }}
    </a>
@stop

{{-- Page content --}}
@section('content')
    <x-container class="col-md-6 col-md-offset-3">
        <x-form :$item route="{{ $item->id ? route('maintenance-types.update', $item->id) : route('maintenance-types.store') }}">
            <x-box>
                <x-form.row
                        :label="trans('general.name')"
                        :$item
                        name="name"
                        required="true"
                />
            </x-box>
        </x-form>
    </x-container>
@stop
