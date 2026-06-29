<div class="box box-default label-config-panel">
    <div class="box-header with-border">
        <x-form.legend>
            {{ trans($section['label']) }}
        </x-form.legend>
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
                        <label>
                            @if ($field['type'] === 'checkbox')
                                <input type="hidden" name="{{ $name }}" value="0">

                                <input
                                        type="checkbox"
                                        name="{{ $name }}"
                                        value="1"
                                        @checked((bool) $value)
                                >

                                {{ trans("admin/labels/general.fields.{$fieldKey}") }}

                            @elseif ($field['type'] === 'number')

                                <div class="supports-number-field">
                                    <input
                                            id="{{ $name }}"
                                            type="number"
                                            name="{{ $name }}"
                                            value="{{ $value }}"
                                            class="form-control"
                                    >

                                    <label for="{{ $name }}">
                                        {{ trans("admin/labels/general.fields.{$fieldKey}") }}
                                    </label>
                                </div>

                            @else

                                {{ trans("admin/labels/general.fields.{$fieldKey}") }}

                                <input
                                        type="{{ $field['type'] }}"
                                        name="{{ $name }}"
                                        value="{{ $value }}"
                                        class="form-control"
                                >

                            @endif
                        </label>
                    </div>
                @endforeach
            </div>
        @elseif (isset($section['groups']))
            <div class="label-content-groups">
                @foreach ($section['groups'] as $groupKey => $group)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>{{ trans($group['label']) }}</strong>
                        </div>

                        <div class="panel-body">
                            @foreach ($group['fields'] as $fieldKey => $field)
                                @include('partials.label-editor-field', [
                                    'sectionKey' => $group['section_key'] ?? $groupKey,
                                    'fieldKey' => $fieldKey,
                                    'field' => $field,
                                    'config' => $config,
                                ])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @foreach ($section['fields'] as $fieldKey => $field)
                @include('partials.label-editor-field', [
                    'sectionKey' => $sectionKey,
                    'fieldKey' => $fieldKey,
                    'field' => $field,
                    'config' => $config,
                ])
            @endforeach
        @endif
    </div>
</div>