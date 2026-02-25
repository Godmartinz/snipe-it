@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/suppliers/table.suppliers') }}
@parent
@stop

{{-- Page content --}}
@section('content')
    <x-container>
        <x-box>

            <x-slot:bulkactions>
                <x-table.bulk-actions
                        name='supplier'
                        action_route="{{route('suppliers.bulk.delete')}}"
                        model_name="supplier"
                >
                    @can('delete', App\Models\Supplier::class)
                        <option>{{ trans('general.delete') }}</option>
                    @endcan
                </x-table.bulk-actions>
            </x-slot:bulkactions>


            <x-table
                name="supplier"
                buttons="supplierButtons"
                fixed_right_number="1"
                fixed_number="1"
                api_url="{{ route('api.suppliers.index') }}"
                :presenter="\App\Presenters\SupplierPresenter::dataTableLayout()"
                export_filename="export-suppliers-{{ date('Y-m-d') }}"
            />

        </x-box>
    </x-container>
@stop

@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'suppliers-export', 'search' => true])
@stop
