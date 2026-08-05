@use('App\Helpers\Helper')

@props([
    'inputId' => 'fileUpload',
])

<x-form.row
    :label="trans('general.file_upload')"
    name="file"
    input_div_class="col-md-9"
    :id="$inputId.'-upload'"
    :errors_class="$errors->has('file.*') ? ' has-error' : ''"
>
    <x-slot:input>
        <label class="btn btn-sm btn-theme" for="{{ $inputId }}">
            {{ trans('button.select_files') }}
            <input
                type="file"
                name="file[]"
                multiple
                class="js-uploadFile"
                id="{{ $inputId }}"
                data-maxsize="{{ Helper::file_upload_max_size() }}"
                accept="{{ config('filesystems.allowed_upload_mimetypes') }}"
                style="display:none"
                aria-label="file"
                aria-hidden="true"
            >
        </label>

        <span id="{{ $inputId }}-info"></span>
        <p class="help-block" id="{{ $inputId }}-status">
            {{ trans('general.upload_filetypes_help', ['allowed_filetypes' => config('filesystems.allowed_upload_extensions'), 'size' => Helper::file_upload_max_size_readable()]) }}
        </p>

        @foreach ($errors->get('file.*') as $messages)
            @foreach ($messages as $message)
                <span class="alert-msg" role="alert" aria-live="assertive">{{ $message }}</span><br>
            @endforeach
        @endforeach
    </x-slot:input>
</x-form.row>
