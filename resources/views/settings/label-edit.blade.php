@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('admin/settings/general.labels_title') }}
    @parent
@stop

@section('header_right')
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
                <form id="label-customizer-form" method="POST" action="#" class="form-horizontal">
                    @csrf

                    @php
                        $printable = $config['label_printable_area'] ?? $config['printable_area'] ?? null;
                    @endphp
                    <input type="hidden" name="template" value="{{ $selectedLabel ?: 'DefaultLabel' }}">
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
                                        value="{{ old('name', $defaultName) }}"
                                        placeholder="Enter label name"
                                        style="max-width: 320px;"
                                >
                            </div>
                            <p class="text-muted">
                                Unit: {{ $config['unit'] }} (applies to all dimensions)
                            </p>
                        </div>

                        @if(!empty($config['page']))
                            <div class="panel box box-default label-config-panel">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Page</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-toggle="collapse"
                                                data-target="#page-config">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="page-config" class="box-body collapse in">
                                    @foreach($config['page'] as $key => $value)
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">{{ $key }}</label>
                                            <div class="col-md-7">
                                                <input type="number" step="0.001" name="page[{{ $key }}]"
                                                       value="{{ $value }}" class="form-control">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($config['grid']))
                            <div class="panel box box-default label-config-panel">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Grid</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-toggle="collapse"
                                                data-target="#grid-config">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="grid-config" class="box-body collapse in">
                                    @foreach($config['grid'] as $key => $value)
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">{{ $key }}</label>
                                            <div class="col-md-7">
                                                <input type="number" step="0.001" name="grid[{{ $key }}]"
                                                       value="{{ $value }}" class="form-control">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($config['label']))
                            <div class="panel box box-default label-config-panel">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Label</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-toggle="collapse"
                                                data-target="#label-config">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="label-config" class="box-body collapse in">
                                    @foreach($config['label'] as $key => $value)
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">{{ $key }}</label>
                                            <div class="col-md-7">
                                                <input type="number" step="0.001" name="label[{{ $key }}]"
                                                       value="{{ $value }}" class="form-control">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($printable))
                            <div class="panel box box-default label-config-panel">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Printable Area</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-toggle="collapse"
                                                data-target="#printable-config">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="printable-config" class="box-body collapse in">
                                    @foreach($printable as $key => $value)
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">{{ $key }}</label>
                                            <div class="col-md-7">
                                                <input type="number" step="0.001" value="{{ $value }}"
                                                       class="form-control" disabled>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($config['content']))
                            <div class="panel box box-default label-config-panel">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Content</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-toggle="collapse"
                                                data-target="#content-config">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="content-config" class="box-body collapse in">
                                    @foreach($config['content'] as $key => $value)
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">{{ $key }}</label>
                                            <div class="col-md-7">
                                                <input type="number" step="0.001" name="content[{{ $key }}]"
                                                       value="{{ $value }}" class="form-control">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($config['supports']))
                            <div class="panel box box-default label-config-panel">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Supports</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-toggle="collapse"
                                                data-target="#supports-config">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="supports-config" class="box-body collapse in">
                                    @foreach($config['supports'] as $key => $value)
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">{{ $key }}</label>
                                            <div class="col-md-7">
                                                @if(is_bool($value))
                                                    <input type="hidden" name="supports[{{ $key }}]" value="0">
                                                    <label class="form-control" style="height:auto; min-height:34px;">
                                                        <input type="checkbox" name="supports[{{ $key }}]"
                                                               value="1" {{ $value ? 'checked' : '' }}>
                                                        {{ $key }}
                                                    </label>
                                                @else
                                                    <input type="number" step="1" name="supports[{{ $key }}]"
                                                           value="{{ $value }}" class="form-control">
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="label-form-footer">
                            <div class="box-footer clearfix" style="padding-left: 0; padding-right: 0;">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-check"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@push('js')
    @livewireScripts
@endpush