<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use Livewire\Component;

class CompanySelect extends Component
{
    public function render()
    {
        return view('livewire.company-select', [

            'snipeSettings' => Setting::getSettings(),
            'translated_name' => trans('general.company'),
            'fieldname'       => $company_id
        ]);
    }
}
