<?php

use App\Http\Controllers\AssetModelsController;
use App\Http\Controllers\BulkAssetModelsController;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// Asset Model Management

Route::group(['prefix' => 'models', 'middleware' => ['auth']], function () {

    Route::get(
        '{model}/clone',
        [
            AssetModelsController::class,
            'getClone',
        ]
    )->where('model', '[0-9]+')
        ->name('models.clone.create')->withTrashed()
        ->breadcrumbs(fn (Trail $trail) => $trail->parent('models.index')
            ->push(trans('admin/models/table.clone'), route('models.index')));

    Route::post(
        '{model}/clone',
        [
            AssetModelsController::class,
            'postCreate',
        ]
    )->where('model', '[0-9]+')
        ->name('models.clone.store')->withTrashed();

    // Legacy URL that predates Route::resource below, which already provides
    // models.show at GET /models/{model}. Kept (pointing at the same controller
    // method) so old bookmarks / external links don't 404.
    Route::get(
        '{model}/view',
        [
            AssetModelsController::class,
            'show',
        ]
    )->where('model', '[0-9]+')
        ->name('view/model');

    Route::post(
        '{modelID}/restore',
        [
            AssetModelsController::class,
            'getRestore',
        ]
    )->where('modelID', '[0-9]+')
        ->name('models.restore.store');

    Route::get(
        '{modelId}/custom_fields',
        [
            AssetModelsController::class,
            'getCustomFields',
        ]
    )->where('modelId', '[0-9]+')
        ->name('custom_fields/model');

    Route::post(
        'bulkedit',
        [
            BulkAssetModelsController::class,
            'edit',
        ]
    )->name('models.bulkedit.index')
        ->breadcrumbs(fn (Trail $trail) => $trail->parent('models.index')
            ->push(trans('general.bulk_edit'), route('models.index')));

    Route::post(
        'bulksave',
        [
            BulkAssetModelsController::class,
            'update',
        ]
    )->name('models.bulkedit.store');

    Route::post(
        'bulkdelete',
        [
            BulkAssetModelsController::class,
            'destroy',
        ]
    )->name('models.bulkdelete.store');

});

Route::resource('models', AssetModelsController::class, [
    'middleware' => ['auth'],
])->withTrashed();
