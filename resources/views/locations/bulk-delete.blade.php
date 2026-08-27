@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('general.bulk.delete.header', ['object_type' => trans_choice('general.location_plural', $valid_count)]) }}
    @parent
@stop

@section('header_right')
    <a href="{{ URL::previous() }}" class="btn btn-primary pull-right">
        {{ trans('general.back') }}</a>
@stop

{{-- Page content --}}
@section('content')
    <x-container class="col-md-8 col-md-offset-2">
        <x-form route="{{ route('locations.bulkdelete.store') }}">
            <x-box>
                <x-slot:header>
                    <span style="color: red">
                        {{ trans_choice('general.bulk.delete.warn', $valid_count, ['count' => $valid_count, 'object_type' => trans_choice('general.location_plural', $valid_count)]) }}
                    </span>
                </x-slot:header>

                <table class="table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="col-md-1">
                                <label>
                                    <input type="checkbox" id="checkAll" checked data-toggle="check-all">
                                </label>
                            </td>
                            <td class="col-md-10">{{ trans('general.name') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                            <tr{!! (($location->assets_count > 0) ? ' class="danger"' : '') !!}>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $location->id }}" {!! (($location->isDeletable()) ? ' checked="checked"' : ' disabled') !!}>
                                </td>
                                <td>{{ $location->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <x-slot:customfooter>
                    <div class="box-footer text-right">
                        <a class="btn btn-link pull-left" href="{{ URL::previous() }}">{{ trans('button.cancel') }}</a>
                        <button type="submit" class="btn btn-success" id="submit-button">
                            <x-icon type="checkmark" /> {{ trans('general.delete') }}
                        </button>
                    </div>
                </x-slot:customfooter>
            </x-box>
        </x-form>
    </x-container>
@stop
