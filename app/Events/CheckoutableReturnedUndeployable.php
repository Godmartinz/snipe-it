<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CheckoutableReturnedUndeployable
{
    use Dispatchable, SerializesModels;

    public function __construct($checkoutable, $checkout, $checkedOutTo, User $returnedBy, $note, $action_date = null)
    {
        $this->checkoutable = $checkoutable;
        $this->checkout = $checkout;
        $this->checkedOutTo = $checkedOutTo;
        $this->returnedBy = $returnedBy;
        $this->note = $note;
        $this->action_date = $action_date ?? date('Y-m-d H:i:s');
    }
}