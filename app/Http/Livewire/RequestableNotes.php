<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Request;
use Livewire\Component;
use App\Models\Asset;

class RequestableNotes extends Component
{
    public $requested_asset;
    public $requested_licenses= [];
    public $title;
    public $requested_accessories;
    public $request;

    public function saveRequest(){

    }

    public function render()
    {
        return view('livewire.Requestable-Notes');

    }
}
