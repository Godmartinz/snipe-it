<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RequestableButton extends Component
{
    public $requested_asset;
    public function mount($id){
        $this->requested_asset = Asset::find($id);
    }
    public function render()
    {
        return view('livewire.requestable-button')->with($this->requested_asset);
    }
}
