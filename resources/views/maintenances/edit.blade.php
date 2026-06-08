@extends('layouts/default')

{{-- Page title --}}
@section('title')
  @if ($item->id)
    {{ trans('admin/maintenances/form.update') }}
  @else
    {{ trans('admin/maintenances/form.create') }}
  @endif
  @parent
@stop


@section('header_right')
<a href="{{ URL::previous() }}" class="btn btn-primary pull-right">
  {{ trans('general.back') }}</a>
@stop


{{-- Page content --}}
@section('content')

<div class="row">
    <div class="col-md-6 col-md-offset-3">
    @if ($item->id)
      <form class="form-horizontal" method="post" action="{{ route('maintenances.update', $item->id) }}" autocomplete="off" enctype="multipart/form-data">
      {{ method_field('PUT') }}
    @else
      <form class="form-horizontal" method="post" action="{{ route('maintenances.store') }}" autocomplete="off" enctype="multipart/form-data">
    @endif
    <!-- CSRF Token -->
    {{ csrf_field() }}

    <div class="box box-default">

        @if ($item->id)
          <div class="box-header with-border">
            <h2 class="box-title">
              {{ $item->title }}
            </h2>
          </div><!-- /.box-header -->
        @endif

      <div class="box-body">

        @include ('partials.forms.edit.name', ['translated_name' => trans('general.name'), 'required' => 'true'])

        <!-- This is a new maintenance -->
        @if (!$item->id)


          @include ('partials.forms.edit.asset-select', [
            'translated_name' => trans('general.assets'),
            'fieldname' => 'selected_assets[]',
            'multiple' => true,
            'required' => true,
            'select_id' => 'assigned_assets_select',
            'asset_selector_div_id' => 'assets_for_maintenance_div',
            'asset_ids' => $item->id ? $item->asset()->pluck('id')->toArray() : old('selected_assets'),
            'asset_id' => $item->id ? $item->asset()->pluck('id')->toArray() : null
          ])
        @else

          @if ($item->asset->company)
            <div class="form-group">
              <label for="company" class="control-label col-md-3">
                {{ trans('general.company') }}
              </label>

              <div class="col-md-9">
                <p class="form-control-static">
                  {{  $item->asset->company->name }}
                </p>
              </div>
            </div>
          @endif

            <div class="form-group">
              <label for="asset" class="control-label col-md-3">
                {{ trans('general.asset') }}
              </label>

              <div class="col-md-9">
                <p class="form-control-static">
                  {{ $item->asset ? $item->asset->present()->fullName : '' }}
                </p>
              </div>
            </div>

            @if ($item->asset->location)
              <div class="form-group">
                <label for="location" class="control-label col-md-3">
                  {{ trans('general.location') }}
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ $item->asset->location->name }}
                  </p>
                </div>
              </div>
            @endif

        @endif


        @include ('partials.forms.edit.maintenance_type')

          <!-- Responsible Party -->
          <div class="form-group {{ $errors->has('responsible_party_id') ? ' has-error' : '' }}">
              <label for="responsible_party_id" class="col-md-3 control-label">
                  {{ trans('admin/maintenances/form.responsible_party') }}
              </label>
              <div class="col-md-7">
                  <select
                      class="js-data-ajax select2"
                      data-endpoint="users"
                      name="responsible_party_id"
                      id="responsible_party_id"
                      data-placeholder="{{ trans('general.select_user') }}"
                      aria-label="responsible_party_id"
                      style="width: 100%;"
                  >
                      @if ($item->responsibleParty)
                          <option value="{{ $item->responsibleParty->id }}" selected="selected">
                              {{ $item->responsibleParty->display_name }}
                          </option>
                      @elseif (! $item->id)
                          <option value="{{ auth()->id() }}" selected="selected">
                              {{ auth()->user()->display_name }}
                          </option>
                      @endif
                  </select>
                  {!! $errors->first('responsible_party_id', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
              </div>
          </div>

          <!-- Start Date -->
        <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
          <label for="start_date" class="col-md-3 control-label">
            {{ trans('admin/maintenances/form.start_date') }}
          </label>

          <div class="col-md-4">
            <x-input.datepicker
                    name="start_date"
                    :value="old('start_date', $item->start_date)"
                    placeholder="{{ trans('general.select_date') }}"
                    required="{{ Helper::checkIfRequired($item, 'start_date') }}"
            />
            {!! $errors->first('start_date', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
          </div>
        </div>



        <!-- Completion Date -->
        <div class="form-group {{ $errors->has('completion_date') ? ' has-error' : '' }}">
          <label for="start_date" class="col-md-3 control-label">{{ trans('admin/maintenances/form.completion_date') }}</label>

          <div class="input-group col-md-4">
            <x-input.datepicker
                    name="completion_date"
                    :value="old('start_date', $item->completion_date)"
                    placeholder="{{ trans('general.select_date') }}"
                    required="Helper::checkIfRequired($item, 'completion_date')"
            />
            {!! $errors->first('completion_date', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
          </div>
        </div>

        @include ('partials.forms.edit.supplier-select', ['translated_name' => trans('general.supplier'), 'fieldname' => 'supplier_id'])


        <!-- Warranty -->
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
              <label class="form-control">
                <input type="checkbox" value="1" name="is_warranty" id="is_warranty" {{ old('is_warranty', $item->is_warranty) == '1' ? ' checked="checked"' : '' }}>
                {{ trans('admin/maintenances/form.is_warranty') }}
              </label>
          </div>
        </div>


        <!-- Asset Maintenance Cost -->
        <div class="form-group {{ $errors->has('cost') ? ' has-error' : '' }}">
          <label for="cost" class="col-md-3 control-label">{{ trans('admin/maintenances/form.cost') }}</label>
            <div class="col-md-9">
                <div class="input-group col-md-5" style="padding-left: 0px;">
                    <input class="form-control" type="text" inputmode="decimal" pattern="[\d.,]+" name="cost" aria-label="cost" id="cost" value="{{ old('cost', \App\Helpers\Helper::formatCurrencyOutput($item->cost)) }}" maxlength="25" data-msg-pattern="{{ trans('general.purchase_cost_invalid') }}"/>
              <span class="input-group-addon">
                @if (($item->asset) && ($item->asset->location) && ($item->asset->location->currency != ''))
                  {{ $item->asset->location->currency }}
                @else
                  {{ $snipeSettings->default_currency }}
                @endif
              </span>
                </div>
                <div class="col-md-9" style="padding-left: 0px;">
              {!! $errors->first('cost', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
              <p class="help-block">{{ trans('general.purchase_cost_format_help', ['format' => $snipeSettings->digit_separator]) }}</p>
            </div>
          </div>
        </div>

        <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
          <label for="url" class="col-md-3 control-label">{{ trans('general.url') }}</label>
          <div class="col-md-7">
            <input class="form-control" name="url" type="url" id="url" value="{{ old('url', $item->url) }}" placeholder="https://example.com">
            {!! $errors->first('url', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
          </div>
        </div>


        @include ('partials.forms.edit.image-upload', ['image_path' => app('maintenances_path')])
        @include ('partials.forms.edit.file-upload', ['input_id' => 'maintenanceFileUpload'])


        <!-- Notes -->
        <div class="form-group {{ $errors->has('notes') ? ' has-error' : '' }}">
          <label for="notes" class="col-md-3 control-label">{{ trans('admin/maintenances/form.notes') }}</label>
          <div class="col-md-7">
            <textarea class="col-md-6 form-control" id="notes" name="notes">{{ old('notes', $item->notes) }}</textarea>
            <p class="help-block">{!! trans('general.markdown') !!}</p>
            {!! $errors->first('notes', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
          </div>
        </div>
      </div> <!-- .box-body -->

      <div class="box-footer text-right">
        <button type="submit" class="btn btn-success"><x-icon type="checkmark" /> {{ trans('general.save') }}</button>
      </div>
    </div> <!-- .box-default -->
    </form>
  </div>
</div>

@stop
