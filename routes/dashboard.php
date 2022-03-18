<?php

use App\Http\Controllers\Admin\MedicalTestCategoryAdminController;

Route::middleware('auth')->prefix('dashboard')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/medical-test-category', [MedicalTestCategoryAdminController::class, 'index'])
        ->name('dashboard.medical-test-category.index');


});
