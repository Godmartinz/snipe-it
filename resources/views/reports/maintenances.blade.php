@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('general.asset_maintenance_report') }}
    @parent
@stop

{{-- Page content --}}
@section('content')
    <x-container>
        <x-box>
            <table
                    data-cookie-id-table="maintenancesReport"
                    data-columns="{{ \App\Presenters\MaintenancesPresenter::reportLayout() }}"
                    data-show-footer="true"
                    data-id-table="maintenancesReport"
                    data-side-pagination="server"
                    data-sort-order="asc"
                    id="maintenancesReport"
                    data-advanced-search="false"
                    data-url="{{route('api.maintenances.index', ['format' => 'flat']) }}"
                    class="table table-striped snipe-table"
                    data-export-options='{
                        "fileName": "maintenance-report-{{ date('Y-m-d') }}",
                        "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                        }'>

            </table>
        </x-box>
    </x-container>
@stop

@section('moar_scripts')
    @include ('partials.bootstrap-table')
@stop
