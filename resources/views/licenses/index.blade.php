@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/licenses/general.software_licenses') }}
@parent
@stop


{{-- Page content --}}
@section('content')
    <x-container>
        <x-box>

            <x-slot:bulkactions>
                <x-table.bulk-actions
                    name='licenses'
                    action_route="{{ route('licenses.bulk.delete') }}"
                    model_name="license"
                >
                    @can('delete', App\Models\License::class)
                        <option value="delete">{{ trans('general.delete') }}</option>
                    @endcan
                </x-table.bulk-actions>
            </x-slot:bulkactions>

            <x-table.licenses
                fixed_right_number="2"
                fixed_number="1"
                show_footer="true"
                show_advanced_search="true"
                name="licenses"
                :route="route('api.licenses.index', ['status' => e(request('status'))])"/>

        </x-box>
    </x-container>
@stop

@section('moar_scripts')
@include ('partials.bootstrap-table')

@stop
