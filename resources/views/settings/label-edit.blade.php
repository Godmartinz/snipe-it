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
        .label-customizer-layout {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 140px);
            min-height: 600px;
        }

        .label-preview-panel {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #fff;
            padding-bottom: 12px;
            border-bottom: 1px solid #ddd;
            flex-shrink: 0;
        }

        .label-form-panel {
            flex: 1 1 auto;
            overflow-y: auto;
            padding-top: 15px;
            min-height: 0;
        }

        .label-config-section {
            margin-bottom: 24px;
        }

        .label-config-section h3 {
            margin-top: 0;
            margin-bottom: 12px;
        }

        .label-config-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .label-config-row label {
            min-width: 180px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .label-config-row input[type="number"],
        .label-config-row input[type="text"] {
            max-width: 220px;
        }
    </style>
    <div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2">
        <div class="label-customizer-layout">
            <fieldset name="label-preview" class="label-preview-panel">
                <div class="col-md-12" style="margin-bottom: 10px;">
                    @include('partials.label2-preview')
                </div>
            </fieldset>

            <div class="label-form-panel">
                <form method="POST" action="#">
                    @csrf

                    <h2>{{ $selectedLabel ?: 'Default Label' }}</h2>
                    <p>Unit: {{ $config['unit'] }}</p>

                    @if(!empty($config['page']))
                        <div class="label-config-section panel panel-default">
                            <div class="panel-heading" style="padding: 0;">
                                <button type="button"
                                        class="btn btn-link btn-block text-left"
                                        data-toggle="collapse"
                                        data-target="#page-config"
                                        aria-expanded="true"
                                        aria-controls="page-config"
                                        style="padding: 12px 15px; text-decoration: none;">
                                    <strong>Page</strong>
                                </button>
                            </div>
                            <div id="page-config" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    @foreach($config['page'] as $key => $value)
                                        <div class="label-config-row">
                                            <label>{{ $key }}</label>
                                            <input type="number" step="0.001" name="page[{{ $key }}]"
                                                   value="{{ $value }}" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($config['grid']))
                        <div class="label-config-section panel panel-default">
                            <div class="panel-heading" style="padding: 0;">
                                <button type="button"
                                        class="btn btn-link btn-block text-left"
                                        data-toggle="collapse"
                                        data-target="#grid-config"
                                        aria-expanded="false"
                                        aria-controls="grid-config"
                                        style="padding: 12px 15px; text-decoration: none;">
                                    <strong>Grid</strong>
                                </button>
                            </div>
                            <div id="grid-config" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    @foreach($config['grid'] as $key => $value)
                                        <div class="label-config-row">
                                            <label>{{ $key }}</label>
                                            <input type="number" step="0.001" name="grid[{{ $key }}]"
                                                   value="{{ $value }}" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($config['label']))
                        <div class="label-config-section panel panel-default">
                            <div class="panel-heading" style="padding: 0;">
                                <button type="button"
                                        class="btn btn-link btn-block text-left"
                                        data-toggle="collapse"
                                        data-target="#label-config"
                                        aria-expanded="false"
                                        aria-controls="label-config"
                                        style="padding: 12px 15px; text-decoration: none;">
                                    <strong>Label</strong>
                                </button>
                            </div>
                            <div id="label-config" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    @foreach($config['label'] as $key => $value)
                                        <div class="label-config-row">
                                            <label>{{ $key }}</label>
                                            <input type="number" step="0.001" name="label[{{ $key }}]"
                                                   value="{{ $value }}" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($config['label_printable_area'] ?? null))
                        <div class="label-config-section panel panel-default">
                            <div class="panel-heading" style="padding: 0;">
                                <button type="button"
                                        class="btn btn-link btn-block text-left"
                                        data-toggle="collapse"
                                        data-target="#printable-area-config"
                                        aria-expanded="false"
                                        aria-controls="printable-area-config"
                                        style="padding: 12px 15px; text-decoration: none;">
                                    <strong>Printable Area</strong>
                                </button>
                            </div>
                            <div id="printable-area-config" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    @foreach($config['label_printable_area'] as $key => $value)
                                        <div class="label-config-row">
                                            <label>{{ $key }}</label>
                                            <input type="number" step="0.001" value="{{ $value }}" class="form-control"
                                                   disabled>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @elseif(!empty($config['printable_area']))
                        <div class="label-config-section panel panel-default">
                            <div class="panel-heading" style="padding: 0;">
                                <button type="button"
                                        class="btn btn-link btn-block text-left"
                                        data-toggle="collapse"
                                        data-target="#printable-area-config"
                                        aria-expanded="false"
                                        aria-controls="printable-area-config"
                                        style="padding: 12px 15px; text-decoration: none;">
                                    <strong>Printable Area</strong>
                                </button>
                            </div>
                            <div id="printable-area-config" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    @foreach($config['printable_area'] as $key => $value)
                                        <div class="label-config-row">
                                            <label>{{ $key }}</label>
                                            <input type="number" step="0.001" value="{{ $value }}" class="form-control"
                                                   disabled>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($config['content']))
                        <div class="label-config-section panel panel-default">
                            <div class="panel-heading" style="padding: 0;">
                                <button type="button"
                                        class="btn btn-link btn-block text-left"
                                        data-toggle="collapse"
                                        data-target="#content-config"
                                        aria-expanded="false"
                                        aria-controls="content-config"
                                        style="padding: 12px 15px; text-decoration: none;">
                                    <strong>Content</strong>
                                </button>
                            </div>
                            <div id="content-config" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    @foreach($config['content'] as $key => $value)
                                        <div class="label-config-row">
                                            <label>{{ $key }}</label>
                                            <input type="number" step="0.001" name="content[{{ $key }}]"
                                                   value="{{ $value }}" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($config['supports']))
                        <div class="label-config-section panel panel-default">
                            <div class="panel-heading" style="padding: 0;">
                                <button type="button"
                                        class="btn btn-link btn-block text-left"
                                        data-toggle="collapse"
                                        data-target="#supports-config"
                                        aria-expanded="false"
                                        aria-controls="supports-config"
                                        style="padding: 12px 15px; text-decoration: none;">
                                    <strong>Supports</strong>
                                </button>
                            </div>
                            <div id="supports-config" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    @foreach($config['supports'] as $key => $value)
                                        <div class="label-config-row">
                                            <label>{{ $key }}</label>

                                            @if(is_bool($value))
                                                <input type="checkbox" name="supports[{{ $key }}]"
                                                       value="1" {{ $value ? 'checked' : '' }}>
                                            @else
                                                <input type="number" step="1" name="supports[{{ $key }}]"
                                                       value="{{ $value }}" class="form-control">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@stop