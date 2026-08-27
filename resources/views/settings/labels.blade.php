@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('admin/settings/general.labels_title') }}
    @parent
@stop


{{-- Page content --}}
@section('content')

    <style>
        .checkbox label {
            padding-right: 40px;
        }
    </style>

    <form method="POST" action="{{ route('settings.labels.save') }}" accept-charset="UTF-8" id="settingsForm" autocomplete="off" class="form-horizontal" role="form">
    <!-- CSRF Token -->
    {{csrf_field()}}

    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2">

            <div class="panel box box-default">
                <div class="box-header with-border">
                    <h2 class="box-title">
                        <x-icon type="labels"/>
                        {{ trans('admin/settings/general.labels') }}
                    </h2>
                </div>
                <div class="box-body">

                    <div class="col-md-12">

                        <div class="form-group{{ $errors->has('label2_enable') ? ' has-error' : '' }}">
                            <div class="col-md-9 col-md-offset-3">
                                <label class="form-control" for="label2_enable">
                                    <input type="checkbox" value="1" name="label2_enable" id="label2_enable" @checked(old('label2_enable', $setting->label2_enable))>
                                    {{ trans('admin/settings/general.label2_enable') }}
                                </label>

                                <x-form.error name="label2_enable" />

                                <p class="help-block">
                                    {!! trans('admin/settings/general.label2_enable_help') !!}
                                </p>
                            </div>
                        </div>

                        @if ($setting->label2_enable)
                            @include('partials.labels-new-engine')
                        @else
                            <input name="label2_template" type="hidden" value="{{ old('label2_template', $setting->label2_template) }}" />
                            <input name="label2_title" type="hidden" value="{{ old('label2_title', $setting->label2_title) }}" />
                            <input name="label2_asset_logo" type="hidden" value="{{ old('label2_asset_logo', $setting->label2_asset_logo) }}" />
                            <input name="label2_fields" type="hidden" value="{{ old('label2_fields', $setting->label2_fields) }}" />
                            @include('partials.labels-legacy-engine')
                        @endif
                    </div>

                </div> <!--/.box-body-->
                <div class="box-footer">
                    <div class="text-left col-md-6">
                        <a class="btn btn-link text-left" href="{{ route('settings.index') }}">{{ trans('button.cancel') }}</a>
                    </div>
                    <div class="text-right col-md-6">
                        <button type="submit" class="btn btn-success">
                            <x-icon type="checkmark"/> {{ trans('general.save') }}</button>
                    </div>

                </div>
            </div> <!-- /box -->
        </div> <!-- /.col-md-8-->
    </div> <!-- /.row-->

    </form>

@stop

@push('js')
    <script nonce="{{ csrf_token() }}">
        // Delete barcodes
        const $purgeButton = $('#purgebarcodes');
        const $purgeIcon = $('#purgebarcodesicon');
        const $purgeStatus = $('#purgebarcodesstatus');
        const $purgeStatusError = $('#purgebarcodesstatus-error');

        if ($purgeButton.length) {
            $purgeButton.click(function () {
                $purgeIcon.html('');
                $purgeStatus.html('').removeClass('text-success text-danger');
                $purgeStatusError.html('');
                $purgeIcon.html('<i class="fas fa-spinner spin"></i> {{ trans('admin/settings/general.barcodes_spinner') }}');
            $.ajax({
                url: '{{ route('api.settings.purgebarcodes') }}',
                type: 'POST',
                headers: {
                    "X-Requested-With": 'XMLHttpRequest',
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                },
                data: {},
                dataType: 'json',

                success: function (data) {
                    console.dir(data);
                    $purgeIcon.html('');
                    $purgeStatus.html('').removeClass('text-danger').addClass('text-success');
                    $purgeStatusError.html('');
                    if (data.message) {
                        $purgeStatus.html('<i class="fas fa-check text-success"></i> ' + data.message);
                    }
                },

                error: function (data) {
                    $purgeIcon.html('<i class="fas fa-exclamation-triangle text-danger"></i>');
                    $purgeStatus.html('Files could not be deleted.').removeClass('text-success').addClass('text-danger');
                    $purgeStatusError.html('');
                    if (data.responseJSON) {
                        $purgeStatusError.html('Error: ' + data.responseJSON.messages);
                    } else {
                        console.dir(data);
                    }

                }


            });
            });
        }

    </script>
    {{-- Can't use @script here because we're not in a livewire component so let's manually load --}}
    @livewireScripts
@endpush
