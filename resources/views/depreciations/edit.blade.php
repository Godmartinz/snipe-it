@extends('layouts/default')

@php
    $helpText = trans('help.depreciations');
    $helpPosition = 'right';
@endphp

{{-- Page title --}}
@section('title')
    @if ($item->id)
        {{ trans('admin/depreciations/general.update') }}
    @else
        {{ trans('admin/depreciations/general.create') }}
    @endif
    @parent
@stop

{{-- Page content --}}
@section('content')

    <x-container class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12 col-sm-offset-0">

        <x-form :$item route="{{ (isset($item->id)) ? route('depreciations.update', ['depreciation' => $item->id]) : route('depreciations.store') }}">

            <x-box>

                <x-form.row
                    :label="trans('admin/depreciations/general.depreciation_name')"
                    :$item
                    name="name"
                />

                <x-form.row
                    :label="trans('admin/depreciations/general.number_of_months')"
                    :$item
                    name="months"
                    type="number"
                    :min="0"
                    :max="3600"
                    input_div_class="col-md-2"
                />

                {{-- Depreciation minimum: an input plus an "amount / percent" select
                     on the same row. Uses <x-form.row>'s <x-slot:input> so the
                     label, error placement, and grid still come from the row
                     wrapper; only the input area itself is hand-authored. --}}
                <x-form.row
                    :label="trans('admin/depreciations/general.depreciation_min')"
                    name="depreciation_min"
                    input_div_class="col-md-9"
                >
                    <x-slot:input>
                        <div style="display: flex;">
                            <input class="form-control" name="depreciation_min" id="depreciation_min" required type="number" value="{{ old('depreciation_min', $item->depreciation_min) }}" style="width: 90px; margin-right: 15px; display: inline-block;" />
                            <select class="form-control select2" name="depreciation_type" id="depreciation_type" data-minimum-results-for-search="Infinity" style="width: 150px; display: inline-block;">
                                <option value="amount" {{ old('depreciation_type', $item->depreciation_type) == 'amount' ? 'selected' : '' }}>{{ trans('general.depreciation_options.amount') }}</option>
                                <option value="percent" {{ old('depreciation_type', $item->depreciation_type) == 'percent' ? 'selected' : '' }}>{{ trans('general.depreciation_options.percent') }}</option>
                            </select>
                        </div>
                    </x-slot:input>
                </x-form.row>

            </x-box>

        </x-form>

    </x-container>

@stop
