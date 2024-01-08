<?php

namespace App\Http\Livewire;

use App\Models\License;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use App\Models\Asset;

class RequestableNotes extends Component
{
    public $asset;
    public $licenses;
    public $license_request;
    public function mount($item){
        $this->licenses = License::all();
        $this->asset = $item->id;
    }

    public function render()
    {
        return view('livewire.requestable-notes');

    }
}
