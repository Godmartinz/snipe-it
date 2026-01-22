<?php

namespace App\Actions;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Component;
use App\Models\LicenseSeat;

class CheckinActionResult
{
    private function __construct(
        public Asset|Accessory|Component|LicenseSeat $item,
        public mixed $target,
        public string $checkinAt,
        public array $originalValues,
    ) {}
}