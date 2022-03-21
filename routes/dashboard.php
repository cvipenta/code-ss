<?php

use App\Http\Controllers\Admin\MedicalTestCategoryAdminController;

Route::middleware('auth')->prefix('dashboard')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('medical-test-categories', MedicalTestCategoryAdminController::class);
//    Route::get('/medical-test-categories', [MedicalTestCategoryAdminController::class, 'index'])
//        ->name('dashboard.medical-test-categories.index');
//
//    Route::get('/medical-test-categories/create', [MedicalTestCategoryAdminController::class, 'create'])
//        ->name('dashboard.medical-test-categories.create');
//    Route::post('/medical-test-categories', [MedicalTestCategoryAdminController::class, 'store'])
//        ->name('dashboard.medical-test-categories.store');
//
//    Route::get('/medical-test-categories/{medicalTestCategory}/edit', [MedicalTestCategoryAdminController::class, 'edit'])
//        ->name('dashboard.medical-test-categories.edit');
//    Route::post('/medical-test-categories/{medicalTestCategory}', [MedicalTestCategoryAdminController::class, 'update'])
//        ->name('dashboard.medical-test-categories.update');
//
//    Route::delete('/medical-test-categories/{medicalTestCategory}', [MedicalTestCategoryAdminController::class, 'destroy'])
//        ->name('dashboard.medical-test-categories.destroy');

});
