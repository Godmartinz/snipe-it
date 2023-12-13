<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Request;
use Livewire\Component;
use App\Models\Asset;

class RequestableNotes extends Component
{

    public function render()
    {
        return view('livewire.Requestable-Notes');

    }
}
