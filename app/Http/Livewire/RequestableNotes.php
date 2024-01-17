<?php

namespace App\Http\Livewire;

use App\Models\Accessory;
use App\Models\License;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use App\Models\Asset;

class RequestableNotes extends Component
{
    public $asset;
    public $licenses;
    public $accessories;
    public $license_request = [];
    public $accessory_request = [];
    public function mount($item){

        $this->asset = $item->id;
    }
    public function addAccessoryRequest($value)
    {
        dd($value);
        $this->accessory_request[] = $value;
//        Log::debug($this->accessory_request);
    }


    public function addLicenseRequest($value)
    {
        $this->license_request[] = $value;
    }

    public function render()
    {
        return view('livewire.requestable-notes');

    }
}
