<?php

namespace App\Providers;

use App\Http\Controllers\Listeners\CheckoutableListener;
use App\Http\Controllers\Listeners\LogListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            \App\Http\Controllers\Listeners\LogSuccessfulLogin::class,
        ],

        'Illuminate\Auth\Events\Failed' => [
            \App\Http\Controllers\Listeners\LogFailedLogin::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        LogListener::class,
        CheckoutableListener::class,
    ];
}
