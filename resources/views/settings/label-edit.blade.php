@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('admin/settings/general.labels_title') }}
    @parent
@stop

@section('header_right')
    <button
            type="submit"
            form="label-customizer-form"
            class="btn btn-success"
    >
        <i class="fa fa-check"></i> Save
    </button>
    <a href="{{ route('settings.labels.index') }}" class="btn btn-primary"> {{ trans('general.back') }}</a>
@stop


{{-- Page content --}}
@section('content')
    <style>
        .label-customizer-shell {
            height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .label-preview-sticky {
            position: sticky;
            top: 0;
            z-index: 20;
            background: #222d32;
            padding-bottom: 12px;
            flex: 0 0 auto;
        }

        .label-form-scroll {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            padding-top: 10px;
        }

        .label-config-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(320px, 1fr));
            gap: 16px;
            align-items: start;
        }

        @media (min-width: 992px) {
            .label-config-grid {
                grid-template-columns: repeat(2, minmax(320px, 1fr));
            }
        }

        .label-config-panel {
            margin-bottom: 0;
        }

        .label-config-panel .box-body {
            padding-right: 15px;
        }

        .label-config-panel .form-group:last-child {
            margin-bottom: 0;
        }

        .label-config-panel .form-control {
            width: 120px;
            max-width: 120px;
        }

        .label-form-header,
        .label-form-footer {
            grid-column: 1 / -1;
        }
    </style>

    <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1">
        <div class="label-customizer-shell">

            <fieldset name="label-preview" class="label-preview-sticky">
                <div class="col-md-12" style="margin-bottom: 10px;">
                    @include('partials.custom-label-preview')
                </div>
            </fieldset>
            <div class="label-form-scroll">
                <form id="label-customizer-form" method="POST" action="{{ $formAction }}" class="form-horizontal">
                    @csrf

                    @if (($formMethod ?? 'POST') !== 'POST')
                        @method($formMethod)
                    @endif
                    @if (! empty($importedConfig))
                        <input type="hidden" name="imported_config_snapshot"
                               value="{{ e(json_encode($importedConfig)) }}">
                    @endif

                    @php
                        $printable = $config['label_printable_area'] ?? $config['printable_area'] ?? null;
                    @endphp
                    <input type="hidden" name="template" value="{{ $selectedLabel ?: 'DefaultLabel' }}">
                    <input
                            type="hidden"
                            name="type"
                            value="{{ $selectedType ?? data_get($config, 'type', 'sheet') }}"
                    >
                    <div class="label-config-grid">
                        <div class="label-form-header">
                            @php
                                $defaultName = $selectedLabel ? 'Copy of '.$selectedLabel : 'Custom Label';
                            @endphp

                            <div class="form-group" style="margin-left: 0; margin-right: 0;">
                                <label for="name" style="display:block; margin-bottom:6px;">
                                    Label Name
                                </label>

                                <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name', data_get($importedConfig ?? [], 'name', $defaultName)) }}"
                                        placeholder="Enter label name"
                                        style="max-width: 320px;"
                                >
                            </div>
                            <p class="text-muted">
                                Unit: {{ $config['unit'] }} (applies to all dimensions)
                            </p>
                        </div>
                        <div class="row">
                            @foreach ($sections as $sectionKey => $section)
                                @continue(empty($section))

                                @php
                                    $isFullWidth = ($section['column_span'] ?? 1) === 2;

                                    $sectionClass = ($section['column_span'] ?? 1) === 2
                                        ? 'col-md-12'
                                        : 'col-md-6';

                                    $inlineFields = ($section['display'] ?? null) === 'inline';
                                @endphp
                                @if ($isFullWidth)
                        </div>
                        <div class="row">
                            @endif

                            <div class="{{ $sectionClass }}">
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">{{ trans($section['label']) }}</h3>
                                    </div>

                                    <div class="box-body">
                                        @if ($inlineFields)
                                            <div class="row">
                                                @foreach ($section['fields'] as $fieldKey => $field)
                                                    @php
                                                        $name = "{$sectionKey}[{$fieldKey}]";
                                                        $value = data_get($config, "{$sectionKey}.{$fieldKey}");
                                                    @endphp

                                                    <div class="col-md-2">
                                                        @if ($field['type'] === 'checkbox')
                                                            <input type="hidden" name="{{ $name }}" value="0">

                                                            <label>
                                                                <input
                                                                        type="checkbox"
                                                                        name="{{ $name }}"
                                                                        value="1"
                                                                        @checked((bool) $value)
                                                                >
                                                                {{ trans("admin/labels/general.fields.{$fieldKey}") }}
                                                            </label>
                                                        @else
                                                            <label>
                                                                {{ trans("admin/labels/general.fields.{$fieldKey}") }}
                                                            </label>

                                                            <input
                                                                    type="{{ $field['type'] }}"
                                                                    name="{{ $name }}"
                                                                    value="{{ $value }}"
                                                                    class="form-control"
                                                            >
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (isset($section['groups']))
                                            @foreach ($section['groups'] as $groupKey => $group)
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <strong>{{ trans($group['label']) }}</strong>
                                                    </div>

                                                    <div class="panel-body">
                                                        @foreach ($group['fields'] as $fieldKey => $field)
                                                            @php
                                                                $name = "{$sectionKey}[{$fieldKey}]";
                                                                $value = data_get($config, "{$sectionKey}.{$fieldKey}");
                                                            @endphp

                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">
                                                                    {{ trans("admin/labels/general.fields.{$fieldKey}") }}
                                                                </label>

                                                                <div class="col-md-4">
                                                                    @if ($field['type'] === 'checkbox')
                                                                        <input type="hidden" name="{{ $name }}"
                                                                               value="0">

                                                                        <input
                                                                                type="checkbox"
                                                                                name="{{ $name }}"
                                                                                value="1"
                                                                                @checked((bool) $value)
                                                                        >
                                                                    @elseif ($field['type'] === 'select')
                                                                        <select name="{{ $name }}"
                                                                                class="form-control">
                                                                            @foreach ($field['options'] as $optionValue => $optionLabel)
                                                                                <option
                                                                                        value="{{ $optionValue }}"
                                                                                        @selected($value == $optionValue)
                                                                                >
                                                                                    {{ trans($optionLabel) }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    @else
                                                                        <input
                                                                                type="{{ $field['type'] }}"
                                                                                name="{{ $name }}"
                                                                                value="{{ $value }}"
                                                                                class="form-control"
                                                                        >
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach ($section['fields'] as $fieldKey => $field)
                                                @php
                                                    $name = "{$sectionKey}[{$fieldKey}]";
                                                    $value = data_get($config, "{$sectionKey}.{$fieldKey}");
                                                @endphp

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">
                                                        {{ trans("admin/labels/general.fields.{$fieldKey}") }}
                                                    </label>

                                                    <div class="col-md-4">
                                                        @if ($field['type'] === 'checkbox')
                                                            <input type="hidden" name="{{ $name }}" value="0">

                                                            <input
                                                                    type="checkbox"
                                                                    name="{{ $name }}"
                                                                    value="1"
                                                                    @checked((bool) $value)
                                                            >
                                                        @elseif ($field['type'] === 'select')
                                                            <select name="{{ $name }}" class="form-control">
                                                                @foreach ($field['options'] as $optionValue => $optionLabel)
                                                                    <option
                                                                            value="{{ $optionValue }}"
                                                                            @selected($value == $optionValue)
                                                                    >
                                                                        {{ trans($optionLabel) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <input
                                                                    type="{{ $field['type'] }}"
                                                                    name="{{ $name }}"
                                                                    value="{{ $value }}"
                                                                    class="form-control"
                                                            >
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="label-form-footer">
        <div class="box-footer clearfix" style="padding-left: 0; padding-right: 0;">

        </div>
    </div>
@stop
@push('js')
    <script>
        $(document).on('change', '.align-sync', function () {
            const key = $(this).data('key');
            const value = $(this).val();

            if (key === 'logo_h_align') {
                $('select[name="content[barcode2D_h_align]"]')
                    .val(value === 'L' ? 'R' : 'L');
            }

            if (key === 'barcode2D_h_align') {
                $('select[name="content[logo_h_align]"]')
                    .val(value === 'L' ? 'R' : 'L');
            }
        });
    </script>
    @livewireScripts
@endpush