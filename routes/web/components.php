<?php

use App\Http\Controllers\Components;
use Illuminate\Support\Facades\Route;

// Components
Route::group(['prefix' => 'components', 'middleware' => ['auth']], function () {
    // Component / componentAsset ids are always numeric auto-increment PKs,
    // so constrain the route parameters at the router. Without this,
    // non-numeric garbage (pen-test scanners hitting things like
    // components/2||(SELECT...) reach the breadcrumb closure which is
    // typed `int $componentID` and throws TypeError, spamming Rollbar.
    // Constraining at the router 404s the request before any controller
    // or breadcrumb runs.
    Route::get(
        '{componentID}/checkout',
        [Components\ComponentCheckoutController::class, 'create']
    )->where('componentID', '[0-9]+')
        ->name('components.checkout.show');

    Route::post(
        '{componentID}/checkout',
        [Components\ComponentCheckoutController::class, 'store']
    )->where('componentID', '[0-9]+')
        ->name('components.checkout.store');

    Route::get(
        '{componentID}/checkin/{backto?}',
        [Components\ComponentCheckinController::class, 'create']
    )->where('componentID', '[0-9]+')
        ->name('components.checkin.show');

    Route::post(
        '{componentID}/checkin/{backto?}',
        [Components\ComponentCheckinController::class, 'store']
    )->where('componentID', '[0-9]+')
        ->name('components.checkin.store');

    Route::get('{component}/clone',
        [Components\ComponentsController::class, 'getClone']
    )->where('component', '[0-9]+')
        ->name('components.clone.create');

});

Route::resource('components', Components\ComponentsController::class, [
    'middleware' => ['auth'],
    'parameters' => ['component' => 'component_id'],
]);
