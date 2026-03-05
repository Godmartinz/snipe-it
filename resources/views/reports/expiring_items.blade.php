<?php
?>
@extends('layouts/default')

@section('title')
    {{ trans('mail.') }}
    @parent
@stop

@section('header_right')
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group mr-2" role="group">
{{--            @if($showDeleted)--}}
{{--                <a href="{{ route('reports/unaccepted_assets') }}" class="btn btn-default" ><i class="fa fa-trash icon-white" aria-hidden="true"></i> {{ trans('general.hide_deleted') }}</a>--}}
{{--            @else--}}
{{--                <a href="{{ route('reports/unaccepted_assets', ['deleted' => 'deleted']) }}" class="btn btn-default" ><i class="fa fa-trash icon-white" aria-hidden="true"></i> {{ trans('general.show_deleted') }}</a>--}}
{{--            @endif--}}
        </div>
        <div class="btn-group mr-2" role="group">
            <form method="POST" action="{{ route('reports/export/unaccepted_assets') }}" accept-charset="UTF-8" class="form-horizontal">
                {{csrf_field()}}
                <button type="submit" class="btn btn-default"><i class="fa fa-download icon-white" aria-hidden="true"></i> {{ trans('general.download_all') }}</button>
            </form>
        </div>
    </div>
@stop
@section('content')
    <div id="expiring-licenses-toolbar">
        <h4>{{ trans('mail.Expiring_Assets_Report') }}</h4>
    </div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-body">
                <table
                        data-cookie-id-table="expiringAssetsReport"
                        data-id-table="expiringAssetsReport"
                        data-side-pagination="client"
                        data-sort-order="asc"
                        data-sort-name="warranty_expires"
                        data-advanced-search="false"
                        id="expiringAssetsReport"
                        data-fixed-number="false"
                        data-fixed-right-number="false"
                        class="table table-striped snipe-table"
                        data-toolbar="#expiring-assets-toolbar"
                        data-export-options='{
                    "fileName": "expiring-assets-report-{{ date('Y-m-d') }}",
                    "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                }'
                >
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">{{ trans('general.id') }}</th>
                            <th data-field="asset_tag" data-sortable="true">{{ trans('admin/hardware/form.tag') }}</th>
                            <th data-field="model" data-sortable="true">{{ trans('admin/hardware/form.model') }}</th>
                            <th data-field="model_number" data-sortable="true">{{ trans('general.model_no') }}</th>
                            <th data-field="purchase_date" data-sortable="true">{{ trans('general.purchase_date') }}</th>
                            <th data-field="eol_rate" data-sortable="true">{{ trans('admin/hardware/form.eol_rate') }}</th>
                            <th data-field="eol_date" data-sortable="true">{{ trans('admin/hardware/form.eol_date') }}</th>
                            <th data-field="warranty_expires" data-sortable="true">{{ trans('admin/hardware/form.warranty_expires') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td>{{ $asset->id }}</td>
                                <td>{{ $asset->asset_tag }}</td>
                                <td>{{ $asset->model->name ?? '' }}</td>
                                <td>{{ $asset->model->model_number ?? '' }}</td>
                                <td>{{ $asset->purchase_date_formatted }}</td>
                                <td>{{ $asset->model->eol ?? '' }}</td>
                                <td>
                                    @if($asset->eol_date)
                                        {{ $asset->eol_formatted_date }} ({{ $asset->eol_diff_for_humans }})
                                    @endif
                                </td>
                                <td>
                                    @if($asset->warranty_expires)
                                        {{ $asset->warranty_expires_formatted_date }} ({{ $asset->warranty_expires_diff_for_humans }})
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="expiring-licenses-toolbar">
    <h4>{{ trans('mail.Expiring_Licenses_Report') }}</h4>
</div>

    <table
            data-cookie-id-table="expiringLicensesReport"
            data-id-table="expiringLicensesReport"
            data-side-pagination="client"
            data-sort-order="asc"
            data-sort-name="expiration_date"
            data-advanced-search="false"
            id="expiringLicensesReport"
            data-fixed-number="false"
            data-fixed-right-number="false"
            class="table table-striped snipe-table"
            data-toolbar="#expiring-licenses-toolbar"
            data-export-options='{
        "fileName": "expiring-licenses-report-{{ date('Y-m-d') }}",
        "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
    }'
    >

    <thead>
    <tr>
        <th data-field="id" data-sortable="true">{{ trans('general.id') }}</th>

        <th data-field="name" data-sortable="true">{{ trans('general.name') }}</th>

        <th data-field="purchase_date" data-sortable="true">
            {{ trans('general.purchase_date') }}
        </th>

        <th data-field="expiration" data-sortable="true">
            {{ trans('admin/licenses/form.expiration') }}
        </th>

        <th data-field="expires_in" data-sortable="true">
            {{ trans('mail.expires') }}
        </th>

        <th data-field="termination_date" data-sortable="true">
            {{ trans('admin/licenses/form.termination_date') }}
        </th>

        <th data-field="terminates_in" data-sortable="true">
            {{ trans('mail.terminates') }}
        </th>
    </tr>
    </thead>

    <tbody>

    @foreach($licenses as $license)

        <tr>
            <td>{{ $license->id }}</td>

            <td>{{ $license->name }}</td>

            <td>{{ $license->purchase_date_formatted }}</td>

            <td>{{ $license->expires_formatted_date }}</td>

            <td>
                @if($license->expires_formatted_date)
                    {{ $license->expires_diff_for_humans }}
                @endif
            </td>

            <td>{{ $license->terminates_formatted_date }}</td>

            <td>{{ $license->terminates_diff_for_humans }}</td>

        </tr>

    @endforeach

    </tbody>
</table>
    @stop
