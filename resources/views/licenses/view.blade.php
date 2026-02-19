@extends('layouts/default')

{{-- Page title --}}
@section('title')
  {{ trans('admin/licenses/general.view') }}
  - {{ $license->name }}
  @parent
@stop

@section('header_right')
    <i class="fa-regular fa-2x fa-square-caret-right pull-right" id="expand-info-panel-button" data-tooltip="true" title="{{ trans('button.show_hide_info') }}"></i>
@endsection

{{-- Page content --}}
@section('content')
    <x-container columns="2">
        <x-page-column class="col-md-9 main-panel">
            <x-tabs>
                <x-slot:tabnav>


                    <x-tabs.nav-item
                            name="seats"
                            class="active"
                            icon_type="checkedout"
                            label="{{ trans('general.assigned') }}"
                            count="{{ $license->assignedCount()->count() }}"
                    />

                    <x-tabs.nav-item
                            name="available"
                            icon_type="available"
                            label="{{ trans('general.available') }}"
                            count="{{ $license->availCount()->count() }}"
                    />

                <x-tabs.nav-item
                        name="files"
                        icon_type="files"
                        label="{{ trans('general.files') }}"
                        count="{{ $license->uploads()->count() }}"
                />

                <x-tabs.nav-item
                        name="history"
                        icon_type="history"
                        label="{{ trans('general.history') }}"
                        tooltip="{{ trans('general.history') }}"
                />


                @can('update', $license)
                    <x-tabs.nav-item-upload />
                @endcan
                </x-slot:tabnav>

                <x-slot:tabpanes>

                    <x-tabs.pane name="seats" class="in active">
                        <x-slot:header>
                            {{ trans('general.assigned') }}
                        </x-slot:header>
                        <x-slot:content>

                            <x-table
                                    api_url="{{ route('api.licenses.seats.index', [$license->id, 'status' => 'assigned']) }}"
                                    :presenter="\App\Presenters\LicensePresenter::dataTableLayoutSeats()"
                                    export_filename="export-{{ str_slug($license->name) }}-assigned-{{ date('Y-m-d') }}"
                            />

                        </x-slot:content>
                    </x-tabs.pane>


                    <x-tabs.pane name="available">
                        <x-slot:header>
                            {{ trans('general.available') }}
                        </x-slot:header>
                        <x-slot:content>

                            <x-table
                                    api_url="{{ route('api.licenses.seats.index', [$license->id, 'status' => 'available']) }}"
                                    :presenter="\App\Presenters\LicensePresenter::dataTableLayoutSeats()"
                                    export_filename="export-{{ str_slug($license->name) }}-available-{{ date('Y-m-d') }}"
                            />

                        </x-slot:content>
                    </x-tabs.pane>


                    <!-- start history tab pane -->
                    <x-tabs.pane name="history">
                        <x-slot:header>
                            {{ trans('general.history') }}
                        </x-slot:header>
                        <x-slot:content>
                            <x-table
                                    name="locationHistory"
                                    api_url="{{ route('api.activity.index', ['target_id' => $license->id, 'target_type' => 'license']) }}"
                                    :presenter="\App\Presenters\HistoryPresenter::dataTableLayout()"
                                    export_filename="export-licenses-{{ str_slug($license->name) }}-{{ date('Y-m-d') }}"
                            />
                        </x-slot:content>
                    </x-tabs.pane>
                    <!-- end history tab pane -->


                    <!-- start files tab pane -->
                    @can('licenses.files', $license)
                    <x-tabs.pane name="files">
                        <x-slot:header>
                            {{ trans('general.files') }}
                        </x-slot:header>
                        <x-slot:content>
                            <x-filestable object_type="licenses" :object="$license" />
                        </x-slot:content>
                    </x-tabs.pane>
                    @endcan
                    <!-- end files tab pane -->

                </x-slot:tabpanes>
            </x-tabs>
        </x-page-column>

        <x-page-column class="col-md-3">
            <x-box>
                <x-box.info-panel :infoPanelObj="$license" img_path="{{ app('licenses_upload_url') }}">

                    <x-slot:before_list>

                        @can('update', $license)
                            <a href="{{ route('licenses.edit', $license->id) }}" class="btn btn-warning btn-sm btn-social btn-block hidden-print" style="margin-bottom: 5px;">
                                <x-icon type="edit" />
                                {{ trans('admin/licenses/general.edit') }}
                            </a>
                            <a href="{{ route('clone/license', $license->id) }}" class="btn btn-info btn-block btn-sm btn-social hidden-print" style="margin-bottom: 5px;">
                                <x-icon type="clone" />
                                {{ trans('admin/licenses/general.clone') }}</a>
                        @endcan

                        @can('checkout', $license)

                            @if (($license->availCount()->count() > 0) && (!$license->isInactive()))

                                <a href="{{ route('licenses.checkout', $license->id) }}" class="btn bg-maroon btn-sm btn-social btn-block hidden-print" style="margin-bottom: 5px;">
                                    <x-icon type="checkout" />
                                    {{ trans('general.checkout') }}
                                </a>

                                <a href="#" class="btn bg-maroon btn-sm btn-social btn-block hidden-print" style="margin-bottom: 5px;" data-toggle="modal" data-tooltip="true" title="{{ trans('admin/licenses/general.bulk.checkout_all.enabled_tooltip') }}" data-target="#checkoutFromAllModal">
                                    <x-icon type="checkout-all" />
                                    {{ trans('admin/licenses/general.bulk.checkout_all.button') }}
                                </a>

                            @else
                                <span data-tooltip="true" title="{{ ($license->availCount()->count() == 0) ? trans('admin/licenses/general.bulk.checkout_all.disabled_tooltip') : trans('admin/licenses/message.checkout.license_is_inactive') }}" class="btn bg-maroon btn-sm btn-social btn-block hidden-print disabled" style="margin-bottom: 5px;" data-tooltip="true" title="{{ trans('general.checkout') }}">
                                    <x-icon type="checkout" />
                                    {{ trans('general.checkout') }}
                                  </span>
                                                        <span data-tooltip="true" title="{{ ($license->availCount()->count() == 0) ? trans('admin/licenses/general.bulk.checkout_all.disabled_tooltip') : trans('admin/licenses/message.checkout.license_is_inactive') }}" class="btn bg-maroon btn-sm btn-social btn-block hidden-print disabled" style="margin-bottom: 5px;" data-tooltip="true" title="{{ trans('general.checkout') }}">
                                      <x-icon type="checkout" />
                                      {{ trans('admin/licenses/general.bulk.checkout_all.button') }}
                                  </span>
                            @endif
                        @endcan

                            @can('checkin', $license)

                                @if (($license->seats - $license->availCount()->count()) <= 0 )
                                    <span data-tooltip="true" title=" {{ trans('admin/licenses/general.bulk.checkin_all.disabled_tooltip') }}">
                                        <a href="#"  class="btn btn-primary bg-purple btn-sm btn-social btn-block hidden-print disabled"  style="margin-bottom: 25px;">
                                          <x-icon type="checkin" />
                                         {{ trans('admin/licenses/general.bulk.checkin_all.button') }}
                                        </a>
                                    </span>
                                @else
                                    <a href="#"  class="btn btn-primary bg-purple btn-sm btn-social btn-block hidden-print" style="margin-bottom: 25px;" data-toggle="modal" data-tooltip="true"  data-target="#checkinFromAllModal" data-content="{{ trans('general.sure_to_delete') }} data-title="{{  trans('general.delete') }}" onClick="return false;">
                                    <x-icon type="checkin" />
                                    {{ trans('admin/licenses/general.bulk.checkin_all.button') }}
                                    </a>
                                @endif
                            @endcan

                            @can('delete', $license)

                                @if ($license->availCount()->count() == $license->seats)
                                    <a class="btn btn-block btn-danger btn-sm btn-social delete-asset" data-icon="fa fa-trash" data-toggle="modal" data-title="{{ trans('general.delete') }}" data-content="{{ trans('general.delete_confirm', ['item' => $license->name]) }}" data-target="#dataConfirmModal" onClick="return false;">
                                        <x-icon type="delete" />
                                        {{ trans('general.delete') }}
                                    </a>
                                @else
                                    <span data-tooltip="true" title=" {{ trans('admin/licenses/general.delete_disabled') }}">
                                        <a href="#" class="btn btn-block btn-danger btn-sm btn-social delete-asset disabled" onClick="return false;">
                                          <x-icon type="delete" />
                                          {{ trans('general.delete') }}
                                        </a>
                                      </span>
                                @endif
                            @endcan




                    </x-slot:before_list>
                </x-box.info-panel>
            </x-box>

        </x-page-column>
    </x-container>

@can('checkout', \App\Models\License::class)
    @include ('modals.confirm-action',
          [
              'modal_name' => 'checkoutFromAllModal',
              'route' => route('licenses.bulkcheckout', $license->id),
              'title' => trans('general.modal_confirm_generic'),
              'body' => trans_choice('admin/licenses/general.bulk.checkout_all.modal', 2, ['available_seats_count' => $available_seats_count])
          ])
@endcan


  @can('update', \App\Models\License::class)
    @include ('modals.upload-file', ['item_type' => 'license', 'item_id' => $license->id])
  @endcan

@stop


@section('moar_scripts')
  @include ('partials.bootstrap-table')
@stop
