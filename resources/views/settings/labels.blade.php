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

        .import-toggle .btn {
            background-color: #f5f5f5; /* light neutral */
            color: #333;
        }

        .import-toggle .btn:hover {
            background-color: #e6e6e6;
        }

        .import-toggle .btn.active {
            background-color: #337ab7; /* Bootstrap primary */
            color: #fff;
        }

        .import-toggle .btn:focus,
        .import-toggle .btn:active:focus,
        .import-toggle .btn.active:focus,
        .import-toggle .btn.focus,
        .import-toggle .btn.active.focus {
            outline: none !important;
            box-shadow: none !important;
            -webkit-box-shadow: none !important;
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
    <livewire:labels.new-label-setup/>
    <form id="delete-custom-label-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    <form id="import-label-form" method="POST" enctype="multipart/form-data"
          action="{{ route('settings.labels.import') }}">
        @csrf
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

        $(function () {
            let isPreselecting = false;

            $('#label2TemplateTable').on('check.bs.table', function (e, row) {
                if (isPreselecting) {
                    return;
                }

                const value = row.source === 'custom'
                    ? 'custom:' + row.custom_label_id
                    : row.name;

                $('#label2_template').val(value);
                $('#selected-template-display').text(row.name || value);

                document.getElementById('settingsForm')?.dispatchEvent(new Event('change'));
            });

            $('#label2TemplateTable').on('load-success.bs.table', function () {
                const selected = $('#label2_template').val();

                isPreselecting = true;

                $('#label2TemplateTable').bootstrapTable('getData').forEach((row, index) => {
                    const value = row.source === 'custom'
                        ? 'custom:' + row.custom_label_id
                        : row.name;

                    if (value === selected) {
                        $('#label2TemplateTable').bootstrapTable('check', index);
                    }
                });

                isPreselecting = false;

                document.getElementById('settingsForm')?.dispatchEvent(new Event('change'));
            });
        });


        const deleteLabelUrlTemplate = "{{ route('settings.labels.destroy', ['label' => 'label_id']) }}";
        const editLabelUrlTemplate = "{{ route('settings.labels.edit', ['label' => 'label_id']) }}";

        $(document).on('click', '.copy-label-json', async function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $btn = $(this);
            const originalHtml = $btn.html();

            try {
                const json = decodeURIComponent($btn.data('json'));

                await navigator.clipboard.writeText(json);

                $btn
                    .removeClass('btn-primary')
                    .addClass('btn-success')
                    .html('<i class="fa fa-check"></i>');

                setTimeout(() => {
                    $btn
                        .removeClass('btn-success')
                        .addClass('btn-primary')
                        .html(originalHtml);
                }, 1500);
            } catch (e) {
                console.error(e);

                $btn
                    .removeClass('btn-primary')
                    .addClass('btn-danger')
                    .html('<i class="fa fa-times"></i>');

                setTimeout(() => {
                    $btn
                        .removeClass('btn-danger')
                        .addClass('btn-primary')
                        .html(originalHtml);
                }, 1500);
            }
        });
        $(document).on('click', '.export-label-json', function () {

            const json = decodeURIComponent($(this).data('json'));
            const name = decodeURIComponent($(this).data('name'));

            const blob = new Blob([json], {
                type: 'application/json;charset=utf-8'
            });

            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            const safeName = name.replace(/[^\w\-]+/g, '_');

            a.href = url;
            a.download = `${safeName}.json`;

            document.body.appendChild(a);
            a.click();

            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        });

        $('.import-toggle-btn').on('click', function () {
            const method = $(this).data('method');

            $('.import-toggle-btn')
                .removeClass('btn-primary active')
                .addClass('btn-default');

            $(this)
                .addClass('btn-primary active')
                .removeClass('btn-default');

            $('#import_method').val(method);

            $('#text-input-group').toggle(method === 'text');
            $('#json-input-group').toggle(method === 'json');
        });

        $('#config_file').on('change', function () {
            const hasFile = this.files && this.files.length > 0;

            $('#import-file-button').prop('disabled', !hasFile);
        });

        $('#config_snapshot').on('input', function () {

            const value = $(this).val().trim();

            try {
                JSON.parse(value);

                $('#import-text-button').prop('disabled', false);

                $('#json-validation-message')
                    .hide()
                    .text('');

            } catch (e) {

                $('#import-text-button').prop('disabled', true);

                $('#json-validation-message')
                    .show()
                    .addClass('text-danger')
                    .text('Invalid JSON');
            }
        });

    </script>
    {{-- Can't use @script here because we're not in a livewire component so let's manually load --}}
    @livewireScripts
@endpush
