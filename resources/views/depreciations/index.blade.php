@extends('layouts/default')

{{-- Page title --}}
@section('title')
  {{ trans('general.depreciations')}}
@parent
@stop


{{-- Page content --}}
@section('content')
    <x-container>
        <x-box>
            <x-table
                    show_column_search="false"
                    buttons="depreciationButtons"
                    fixed_right_number="1"
                    fixed_number="1"
                    api_url="{{ route('api.depreciations.index') }}"
                    :presenter="\App\Presenters\DepreciationPresenter::dataTableLayout()"
                    export_filename="export-depreciations-{{ date('Y-m-d') }}"
            />
        </x-box>
    </x-container>
@stop

@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'depreciations-export', 'search' => true])
@stop
