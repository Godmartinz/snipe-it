@extends('layouts/default', [
    'helpTitle' => trans('admin/kits/general.about_kits_title'),
    'helpText' => trans('admin/kits/general.about_kits_text')])

{{-- Web site Title --}}
@section('title')
  {{ trans('general.kits') }}
@parent
@stop

{{-- Content --}}
@section('content')
    <x-container>
        <x-box name="kits">
            <x-table
                :presenter="\App\Presenters\PredefinedKitPresenter::dataTableLayout()"
                :fixed_number="1"
                :fixed_right_number="2"
                use_sticky_css
                buttons="kitButtons"
                api_url="{{ route('api.kits.index') }}"
                export_filename="export-kits-{{ date('Y-m-d') }}"
            />
        </x-box>
    </x-container>
@stop
@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'kits-export', 'search' => true])
@stop
