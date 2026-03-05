<div id="expiring-assets-toolbar">
    <h4>{{ trans('admin/hardware/general.expiring_assets') }}</h4>
</div>

<table
        class="snipe-table table table-striped inventory"
        id="expiring-assets"
        data-toolbar="#expiring-assets-toolbar"
        data-pagination="false"
        data-search="true"
        data-side-pagination="client"
        data-sortable="true"
        data-sort-order="asc"
        data-sort-name="asset_tag"
        data-show-columns="true"
        data-cookie-id-table="expiring-assets"
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
