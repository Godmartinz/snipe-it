<?php

namespace App\Http\Requests;

use App\Helpers\Helper;
use App\Http\Traits\ConvertsBase64ToFiles;
use App\Rules\AllowedUploadExtension;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadFileRequest extends Request
{
    use ConvertsBase64ToFiles;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // AllowedUploadExtension replaces Laravel's `mimes:` rule because
        // `mimes:` content-sniffs, reverse-maps the detected MIME to a
        // single extension, and rejects anything the guesser can't map,
        // even when the client extension is on the allowlist. That was
        // rejecting legitimate uploads (empty .txt, INI-shaped text,
        // Windows-sniffed .csv reporting octet-stream) with a generic
        // "check the form below" error. See issues #12460 and #10387.
        return [
            'file.*' => [
                'bail',
                'required',
                'file',
                new AllowedUploadExtension(config('filesystems.allowed_upload_extensions_array')),
                'max:'.Helper::file_upload_max_size(),
            ],
        ];
    }

    /**
     * Sanitizes (if needed) and Saves a file to the appropriate location
     * Returns the 'short' (storage-relative) filename
     */
    public function handleFile(string $dirname, string $name_prefix, $file): string
    {

        $extension = $file->getClientOriginalExtension();
        // Prefer the content-sniffed extension for the stored name so a
        // rename can't hide the real content type from the filesystem.
        // Fall back to the client extension when finfo returns nothing,
        // otherwise the stored filename ends in a bare "." and the
        // eventual download has no extension.
        $stored_extension = $file->guessExtension() ?: strtolower($extension);
        $file_name = $name_prefix.'-'.str_random(8).'-'.str_slug(basename($file->getClientOriginalName(), '.'.$extension)).'.'.$stored_extension;

        // Check for SVG and sanitize it
        if ($file->getMimeType() === 'image/svg+xml') {
            $uploaded_file = $this->handleSVG($file);
        } else {
            $uploaded_file = file_get_contents($file);
        }

        try {
            Storage::put($dirname.$file_name, $uploaded_file);
        } catch (\Exception $e) {
            Log::debug($e);
        }

        return $file_name;
    }

    public function handleSVG($file)
    {
        $sanitizer = new Sanitizer;
        $dirtySVG = file_get_contents($file->getRealPath());

        return $sanitizer->sanitize($dirtySVG);
    }

    /**
     * Get the validation error messages that apply to the request, but
     * replace the attribute name with the name of the file that was attempted and failed
     * to make it clearer to the user which file is the bad one.
     */
    public function attributes(): array
    {
        $attributes = [];

        if (($this->file) && (is_array($this->file))) {

            for ($i = 0; $i < count($this->file); $i++) {

                try {

                    if ($this->file[$i]) {
                        $attributes['file.'.$i] = $this->file[$i]->getClientOriginalName();
                    }

                } catch (\Exception $e) {
                    $attributes['file.'.$i] = 'Invalid file';
                }

            }
        }

        return $attributes;

    }
}
