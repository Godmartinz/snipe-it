<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Asset;

class RequestableButton extends Component
{
    public $requested_asset;
    public $request;
    public function mount($assetId){
        $this->requested_asset = Asset::RequestableAssets()->find($assetId);

    }
    public function render()
    {
        return view('livewire.requestable-button');
    }
}
