<?php

namespace App\Traits;

use Fruitcake\LaravelDebugbar\Facades\Debugbar;

trait DisablesDebugbar
{
    public function disableDebugbar()
    {
        if (class_exists(Debugbar::class)) {
            Debugbar::disable();
        }
    }
}
