<?php
use Illuminate\Support\Facades\Route;
use Sentech\MediaManager\Http\Controllers\MediaController;

Route::group(['middleware' => ['web', 'admin_locale']], function () {
    Route::prefix(config('app.admin_url') . '/media')->group(function () {
        Route::group(['middleware' => ['admin']], function () {
            Route::get('/', [MediaController::class, 'index'])->name('media.admin.index');
            Route::get('/create',[MediaController::class, 'create'])->name('media.admin.new');
            Route::post('/store', [MediaController::class, 'store'])->name('media.admin.store');
            Route::post('/update/{id}', [MediaController::class, 'update'])->name('media.admin.update');
            Route::delete('/destroy/{id}', [MediaController::class, 'destroy'])->name('media.admin.destroy');
        });
    });
});