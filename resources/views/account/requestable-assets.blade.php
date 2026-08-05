@extends('layouts/default')

@section('title0')
  {{ trans('general.requestable_items') }}
@stop

{{-- Page title --}}
@section('title')
    @yield('title0')  @parent
@stop

{{-- Page content --}}
@section('content')

<div class="row">
    <div class="col-md-12">


        @if (($assets->count() < 1) && ($models->count() < 1) && ($accessories->count() < 1))

            <div class="col-md-12">
                <x-alert type="info" icon="info" :title="trans('general.notification_info')">
                    {{ trans('general.no_requestable') }}
                </x-alert>
            </div>

        @else
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                @if ($assets->count() > 0)
                <li class="active">
                    <a href="#assets" data-toggle="tab" title="{{ trans('general.assets') }}">{{ trans('general.assets') }}
                        <span class="badge badge-secondary"> {{ $assets->count()}}</span>
                    </a>               
                </li>
                @endif
                @if ($models->count() > 0)
                <li>
                    <a href="#models" data-toggle="tab" title="{{ trans('general.asset_models') }}">{{ trans('general.asset_models') }}
                        <span class="badge badge-secondary"> {{ $models->count()}}</span>
                    </a>
                </li>
                @endif
                @if ($accessories->count() > 0)
                <li>
                    <a href="#accessories" data-toggle="tab" title="{{ trans('general.accessories') }}">{{ trans('general.accessories') }}
                        <span class="badge badge-secondary"> {{ $accessories->count()}}</span>
                    </a>
                </li>
                @endif
            </ul>
            <div class="tab-content">
                @if ($assets->count() > 0)
                <div class="tab-pane fade in active" id="assets">
                    <div class="row">
                        <div class="col-md-12">
                            <table
                                data-cookie-id-table="requestableAssetsListingTable"
                                data-id-table="requestableAssetsListingTable"
                                data-side-pagination="server"
                                data-show-export="false"
                                data-show-footer="false"
                                data-sort-order="asc"
                                data-sort-name="name"
                                data-toolbar="#assetsBulkEditToolbar"
                                data-bulk-button-id="#bulkAssetEditButton"
                                data-bulk-form-id="#assetsBulkForm"
                                id="assetsListingTable"
                                class="table table-striped snipe-table"
                                data-url="{{ route('api.assets.requestable', ['requestable' => true]) }}">

                                <thead>
                                    <tr>
                                        <th scope="col" class="col-md-1" data-field="image" data-formatter="imageFormatter" data-sortable="true">{{ trans('general.image') }}</th>
                                        <th scope="col" class="col-md-2" data-field="asset_tag" data-sortable="true" >{{ trans('general.asset_tag') }}</th>
                                        <th scope="col" class="col-md-2" data-field="model" data-sortable="true">{{ trans('admin/hardware/table.asset_model') }}</th>
                                        <th scope="col" class="col-md-2" data-field="model_number" data-sortable="true">{{ trans('admin/models/table.modelnumber') }}</th>
                                        <th scope="col" class="col-md-2" data-field="name" data-sortable="true">{{ trans('admin/hardware/form.name') }}</th>
                                        <th scope="col" class="col-md-3" data-field="serial" data-sortable="true">{{ trans('admin/hardware/table.serial') }}</th>
                                        <th scope="col" class="col-md-2" data-field="location" data-sortable="true">{{ trans('admin/hardware/table.location') }}</th>
                                        <th scope="col" class="col-md-2" data-field="status" data-sortable="true">{{ trans('admin/hardware/table.status') }}</th>
                                        <th scope="col" class="col-md-2" data-field="expected_checkin" data-formatter="dateDisplayFormatter" data-sortable="true">{{ trans('admin/hardware/form.expected_checkin') }}</th>

                                        @foreach(\App\Models\CustomField::get() as $field)
                                            @if (($field->field_encrypted=='0') && ($field->show_in_requestable_list=='1'))
                                                <th scope="col" class="col-md-2" data-field="custom_fields.{{ $field->db_column }}" data-sortable="true">{{ $field->name }}</th>
                                            @endif
                                        @endforeach
                                        <th scope="col" class="col-md-1" data-formatter="assetRequestActionsFormatter" data-field="actions" data-sortable="false">{{ trans('table.actions') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @if ($models->count() > 0)
                <div class="tab-pane fade in {{ ($assets->count() == 0) ? 'active' : '' }}" id="models">
                    <div class="row">
                        <div class="col-md-12">
                                <table
                                        data-toolbar="#toolbar"
                                        class="table table-striped snipe-table"
                                        id="table"
                                        data-id-table="advancedTable"
                                        data-cookie-id-table="requestableAssets">
                                <thead>
                                    <tr role="row">
                                        <th scope="col" class="col-md-1" data-sortable="true">{{ trans('general.image') }}</th>
                                        <th scope="col" class="col-md-6" data-sortable="true">{{ trans('admin/hardware/table.asset_model') }}</th>
                                        <th scope="col" class="col-md-3" data-sortable="true">{{ trans('admin/accessories/general.remaining') }}</th>

                                        <th scope="col" class="col-md-2 actions" data-sortable="false">{{ trans('table.actions') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($models as $requestableModel)
                                        <tr>

                                                <td>

                                                    @if (($requestableModel->image) && ($requestableModel->getImageUrl()))
                                                        <a href="{{ $requestableModel->getImageUrl() }}" data-toggle="lightbox" data-type="image">
                                                            <img src="{{ $requestableModel->getImageUrl() }}" style="max-height: {{ $snipeSettings->thumbnail_max_h }}px; width: auto;" class="img-responsive" alt="">
                                                        </a>
                                                    @endif

                                                </td>

                                                <td>
                                                    @can('view', \App\Models\AssetModel::class)
                                                        <a href="{{ route('models.show', ['model' => $requestableModel->id]) }}">{{ $requestableModel->name }}</a>
                                                    @else
                                                        {{ $requestableModel->name }}
                                                    @endcan
                                                </td>

                                                <td>{{$requestableModel->assets->where('requestable', '1')->count()}}</td>

                                                <td>
                                                    <form  action="{{ route('account/request-item', ['itemType' => 'asset_model', 'itemId' => $requestableModel->id])}}" method="POST" accept-charset="utf-8">
                                                        {{ csrf_field() }}
                                                    <input type="text" style="width: 70px; margin-right: 10px;" class="form-control pull-left" name="request-quantity" value="" placeholder="{{ trans('general.qty') }}">
                                                    @if ($requestableModel->isRequestedBy(Auth::user()))
                                                        <input class="btn btn-danger btn-sm" type="submit" value="{{ trans('button.cancel') }}">
                                                    @else
                                                        <input class="btn btn-primary btn-sm" type="submit" value="{{ trans('button.request') }}">
                                                    @endif
                                                    </form>
                                                </td>
                                        </tr>

                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                @endif

                @if ($accessories->count() > 0)
                <div class="tab-pane fade in {{ (($assets->count() == 0) && ($models->count() == 0)) ? 'active' : '' }}" id="accessories">
                    <div class="row">
                        <div class="col-md-12">
                            <table
                                    class="table table-striped snipe-table"
                                    id="requestableAccessoriesTable"
                                    data-cookie-id-table="requestableAccessories">
                                <thead>
                                    <tr role="row">
                                        <th class="col-md-1" data-sortable="true">{{ trans('general.image') }}</th>
                                        <th class="col-md-5" data-sortable="true">{{ trans('admin/accessories/general.accessory_name') }}</th>
                                        <th class="col-md-2" data-sortable="true">{{ trans('admin/hardware/table.location') }}</th>
                                        <th class="col-md-2" data-sortable="true">{{ trans('admin/accessories/general.remaining') }}</th>
                                        <th class="col-md-2 actions" data-sortable="false">{{ trans('table.actions') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($accessories as $requestableAccessory)
                                        <tr>
                                            <td>
                                                @if (($requestableAccessory->image) && ($requestableAccessory->getImageUrl()))
                                                    <a href="{{ $requestableAccessory->getImageUrl() }}" data-toggle="lightbox" data-type="image">
                                                        <img src="{{ $requestableAccessory->getImageUrl() }}" style="max-height: {{ $snipeSettings->thumbnail_max_h }}px; width: auto;" class="img-responsive">
                                                    </a>
                                                @endif
                                            </td>

                                            <td>
                                                @can('view', \App\Models\Accessory::class)
                                                    <a href="{{ route('accessories.show', ['accessory' => $requestableAccessory->id]) }}">{{ $requestableAccessory->name }}</a>
                                                @else
                                                    {{ $requestableAccessory->name }}
                                                @endcan
                                            </td>

                                            <td>{{ $requestableAccessory->location->name ?? '' }}</td>

                                            <td>{{ $requestableAccessory->numRemaining() }}</td>

                                            <td>
                                                <form action="{{ route('account/request-item', ['itemType' => 'accessory', 'itemId' => $requestableAccessory->id]) }}" method="POST" accept-charset="utf-8">
                                                    {{ csrf_field() }}
                                                    <input type="text" style="width: 70px; margin-right: 10px;" class="form-control pull-left" name="request-quantity" value="1" placeholder="{{ trans('general.qty') }}">
                                                    @if ($requestableAccessory->isRequestedBy(Auth::user()))
                                                        <input class="btn btn-danger btn-sm" type="submit" value="{{ trans('button.cancel') }}">
                                                    @else
                                                        <input class="btn btn-primary btn-sm" type="submit" value="{{ trans('button.request') }}">
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div> <!-- .tab-content-->
        </div> <!-- .nav-tabs-custom -->

        @endif
    </div> <!-- .col-md-12> -->
</div> <!-- .row -->
@stop


@section('moar_scripts')
    @include ('partials.bootstrap-table', [
        'exportFile' => 'requested-export',
        'search' => true,
        'clientSearch' => true,
    ])


    <script nonce="{{ csrf_token() }}">

    $( "a[name='Request']").click(function(event) {
        // event.preventDefault();
        quantity = $(this).closest('td').siblings().find('input').val();
        currentUrl = $(this).attr('href');
        // $(this).attr('href', currentUrl + '?quantity=' + quantity);
        // alert($(this).attr('href'));
    });
</script>
@stop


