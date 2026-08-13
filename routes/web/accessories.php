<?php

use App\Http\Controllers\Accessories;
use App\Http\Controllers\BulkAccessoriesController;
use Illuminate\Support\Facades\Route;

/*
* Accessories
 */
Route::group(['prefix' => 'accessories', 'middleware' => ['auth']], function () {
    Route::get(
        '{accessory}/checkout',
        [Accessories\AccessoryCheckoutController::class, 'create']
    )->name('accessories.checkout.show');

    Route::post(
        '{accessory}/checkout',
        [Accessories\AccessoryCheckoutController::class, 'store']
    )->name('accessories.checkout.store');

    // accessoryID is a numeric auto-increment PK. Constrain at the router
    // so non-numeric garbage (pen-test scanners) 404s here instead of
    // reaching the breadcrumb closure at BreadcrumbsServiceProvider:128
    // which is typed `int $accessoryID` and throws TypeError.
    Route::get(
        '{accessoryID}/checkin/{backto?}',
        [Accessories\AccessoryCheckinController::class, 'create']
    )->where('accessoryID', '[0-9]+')
        ->name('accessories.checkin.show');

    Route::post(
        '{accessoryID}/checkin/{backto?}',
        [Accessories\AccessoryCheckinController::class, 'store']
    )->where('accessoryID', '[0-9]+')
        ->name('accessories.checkin.store');

    Route::get('{accessory}/clone',
        [Accessories\AccessoriesController::class, 'getClone']
    )->name('clone/accessories');

    Route::post('{accessory}/clone',
        [Accessories\AccessoriesController::class, 'postCreate']
    );

    Route::post('{accessory}/adjust-quantity',
        [Accessories\AccessoriesController::class, 'adjustQuantity']
    )->name('accessories.adjust-quantity');

});

Route::resource('accessories', Accessories\AccessoriesController::class, [
    'middleware' => ['auth'],
]);

Route::post('accessories/bulk/delete', [BulkAccessoriesController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('accessories.bulk.delete');
