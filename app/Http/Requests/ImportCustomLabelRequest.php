<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportCustomLabelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'import_method' => ['required', 'in:json,text'],
            'config_file' => ['required_if:import_method,json', 'file', 'mimes:json,txt'],
            'config_snapshot' => ['required_if:import_method,text', 'nullable', 'string'],
        ];
    }

    public function rawConfigJson(): ?string
    {
        return $this->input('import_method') === 'json'
            ? file_get_contents($this->file('config_file')->getRealPath())
            : $this->input('config_snapshot');
    }

}