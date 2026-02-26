@component('mail::message')

{{ trans('general.reminder_checked_out_items', array('reply_to_name' => config('mail.reply_to.name'), 'reply_to_address' => config('mail.reply_to.address')))}}

@component('mail::table')

@if ($assets->count() > 0)

## {{ $assets->count() }} {{ trans('general.assets') }}

<table width="100%">
    <tr>
        <th align="left">{{ trans('mail.asset_tag') }}</th>
        <th align="left">{{ trans('mail.name') }} </th>
        <th align="left">{{ trans('general.category') }}</th>
        <th align="left">{{ trans('admin/hardware/form.model') }}</th>
        <th align="left">{{ trans('admin/hardware/table.location') }}</th>
        <th align="left">{{ trans('admin/hardware/form.serial') }}</th>
        <th align="left">{{ trans('admin/hardware/form.checkout_date') }}</th>
    </tr>


@foreach($assets as $asset)
<tr>
    <td> {{ $asset->asset_tag }} </td>
    <td>{{ $asset->display_name }}</td>
    <td> {{ $asset->model->category->name }}</td>
    <td> {{ $asset->model->name }}</td>
    <td> {{ ($asset->location) ? $asset->location->name : '' }} </td>
    <td> {{ $asset->serial }} </td>
    <td> {{ $asset->last_checkout }}
    @if (($snipeSettings->show_images_in_email =='1') && $asset->getImageUrl())
    <td>
        <img src="{{ asset($asset->getImageUrl()) }}" alt="Asset" style="max-width: 64px;">
    </td>
    @endif
</tr>
@endforeach
</table>
@endif

@if ($licenses->count() > 0)
## {{ $licenses->count() }} {{ trans('general.licenses') }}

<table width="100%">
    <tr>
        <th align="left">{{ trans('mail.name') }} </th>
        <th align="left">{{ trans('mail.serial') }} </th>
        <th align="left">{{ trans('mail.checkout_date') }} </th>
    </tr>
@foreach($licenses as $license)
<tr>
    <td>{{ $license->name }}</td>
    <td>{{ $license->serial }}</td>
    <td>{{ $license->pivot->created_at }}</td>
</tr>
@endforeach
</table>
@endif

@if ($accessories->count() > 0)
## {{ $accessories->count() }} {{ trans('general.accessories') }}

<table width="100%">
    <tr>
        <th align="left">{{ trans('mail.name') }} </th>
        <th></th>
        <th align="left">{{ trans('general.category') }} </th>
        <th align="left">{{ trans('mail.checkout_date') }} </th>
    </tr>
    @foreach($accessories as $accessory)
        <tr>
            <td>{{ $accessory->name }}</td>
            @if (($snipeSettings->show_images_in_email =='1') && $accessory->getImageUrl())
                <td>
                    <img src="{{ asset($accessory->getImageUrl()) }}" alt="Accessory" style="max-width: 64px;">
                </td>
            @endif
            <td>{{ $accessory->category->name }}</td>
            <td>{{ $accessory->pivot->created_at }}</td>
        </tr>
    @endforeach
</table>
@endif

@if ($consumables->count() > 0)
## {{ $consumables->count() }} {{ trans('general.consumables') }}

<table width="100%">
<tr>
    <th align="left">{{ trans('mail.name') }} </th>
    <th align="left">{{ trans('general.category') }} </th>
    <th align="left">{{ trans('mail.checkout_date') }} </th>
</tr>
@foreach($consumables as $consumable)
<tr>
<td>{{ $consumable->name }}</td>
    <td>{{ $consumable->category->name }}</td>
    <td>{{ $consumable->pivot->created_at }}</td>
</tr>
@endforeach
</table>
@endif

@if ($components->count() > 0)
## {{ $components->count() }} {{ trans('general.components') }}
<table width="100%">
<tr><th align="left">{{ trans('mail.name') }} </th> <th></th> </tr>
@foreach($components as $component)
<tr>
<td>{{ $component->name }}</td>
</tr>
@endforeach
</table>
@endif

## {{ $assetsAssets->count() }} {{ trans('mail.assigned_from_assets') }}
<table width="100%">
    <tr>
        <th align="left">{{ trans('mail.assigned_to') }} </th>
        <th align="left">{{ trans('mail.item') }} </th>
        <th align="left">{{ trans('mail.qty') }} </th>
        <th></th>
    </tr>
    @foreach($assetsAccessories as $accessory)
        <tr>
            <td>{{ $accessory->assigned?->asset_tag ?? '' }}</td>
            <td>{{ $accessory->accessory->name }}</td>
            <td></td>
        </tr>
    @endforeach
    @foreach($assetsLicenseSeats as $license)
        <tr>
            <td>{{ $license->asset?->asset_tag}}</td>
            <td>{{ $license->name }}</td>
            <td>{{ $license->serial }}</td>
            <td></td>
        </tr>
    @endforeach
    @foreach($assetsComponents as $asset)
        @foreach($asset->components as $component)
        <tr>
            <td>{{ $asset->asset_tag }}</td>
            <td>{{ $component->name }}</td>
            <td>{{ $component->assigned_qty }}</td>
        </tr>
        @endforeach
    @endforeach
</table>

@endcomponent


@endcomponent
