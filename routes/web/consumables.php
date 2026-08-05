<?php

use App\Http\Controllers\Consumables;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'consumables', 'middleware' => ['auth']], function () {
    Route::get(
        '{consumablesID}/checkout',
        [Consumables\ConsumableCheckoutController::class, 'create']
    )->where('consumablesID', '[0-9]+')
        ->name('consumables.checkout.show');

    Route::post(
        '{consumablesID}/checkout',
        [Consumables\ConsumableCheckoutController::class, 'store']
    )->where('consumablesID', '[0-9]+')
        ->name('consumables.checkout.store');

    Route::get('{consumable}/clone',
        [Consumables\ConsumablesController::class, 'clone']
    )->where('consumable', '[0-9]+')
        ->name('consumables.clone.create');

});

Route::resource('consumables', Consumables\ConsumablesController::class, [
    'middleware' => ['auth'],
    'parameters' => ['consumable' => 'consumable_id'],
]);
