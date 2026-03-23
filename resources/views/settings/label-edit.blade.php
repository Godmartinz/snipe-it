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
    <form method="POST" action="#">
        @csrf

        <h2>{{ $selectedLabel ?: 'Default Label' }}</h2>
        <p>Unit: {{ $config['unit'] }}</p>

        @if(!empty($config['page']))
            <h3>Page</h3>
            @foreach($config['page'] as $key => $value)
                <div>
                    <label>{{ $key }}</label>
                    <input type="number" step="0.001" name="page[{{ $key }}]" value="{{ $value }}">
                </div>
            @endforeach
        @endif

        @if(!empty($config['grid']))
            <h3>Grid</h3>
            @foreach($config['grid'] as $key => $value)
                <div>
                    <label>{{ $key }}</label>
                    <input type="number" step="0.001" name="grid[{{ $key }}]" value="{{ $value }}">
                </div>
            @endforeach
        @endif

        @if(!empty($config['label']))
            <h3>Label</h3>
            @foreach($config['label'] as $key => $value)
                <div>
                    <label>{{ $key }}</label>
                    <input type="number" step="0.001" name="label[{{ $key }}]" value="{{ $value }}">
                </div>
            @endforeach
        @endif

        @if(!empty($config['label_printable_area'] ?? null))
            <h3>Printable Area</h3>
            @foreach($config['label_printable_area'] as $key => $value)
                <div>
                    <label>{{ $key }}</label>
                    <input type="number" step="0.001" value="{{ $value }}" disabled>
                </div>
            @endforeach
        @elseif(!empty($config['printable_area']))
            <h3>Printable Area</h3>
            @foreach($config['printable_area'] as $key => $value)
                <div>
                    <label>{{ $key }}</label>
                    <input type="number" step="0.001" value="{{ $value }}" disabled>
                </div>
            @endforeach
        @endif

        @if(!empty($config['content']))
            <h3>Content</h3>
            @foreach($config['content'] as $key => $value)
                <div>
                    <label>{{ $key }}</label>
                    <input type="number" step="0.001" name="content[{{ $key }}]" value="{{ $value }}">
                </div>
            @endforeach
        @endif

        @if(!empty($config['supports']))
            <h3>Supports</h3>
            @foreach($config['supports'] as $key => $value)
                <div>
                    <label>{{ $key }}</label>

                    @if(is_bool($value))
                        <input type="checkbox" name="supports[{{ $key }}]" value="1" {{ $value ? 'checked' : '' }}>
                    @else
                        <input type="number" step="1" name="supports[{{ $key }}]" value="{{ $value }}">
                    @endif
                </div>
            @endforeach
        @endif

        @if(!empty($config['meta']))
            <h3>Meta</h3>
            @foreach($config['meta'] as $key => $value)
                <div>
                    <label>{{ $key }}</label>
                    <input type="text" value="{{ $value }}" disabled>
                </div>
            @endforeach
        @endif

        <button type="submit">Save</button>
    </form>
@stop