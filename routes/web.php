<?php

use App\Http\Controllers\MedicalTestController;
use App\Http\Controllers\Articole;
use App\Http\Controllers\ArticoleRss;
use App\Http\Controllers\Contact;
use App\Http\Controllers\DictionarMedical;
use App\Http\Controllers\DictionarMedicamente;
use App\Http\Controllers\Homepage;
use App\Http\Controllers\MedicalTestCategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// temporary routes
Route::get('/', [Homepage::class, 'index']);

Route::get('/dictionar-medical.html', [DictionarMedical::class, 'index']);
Route::get('/dictionar-medicamente.html', [DictionarMedicamente::class, 'index']);

Route::get('/articole-medicale.html', [Articole::class, 'index']);
Route::get('/articole-medicale-rss.html', [ArticoleRss::class, 'index']);
Route::get('/contact.html', [Contact::class, 'index']);

Route::get('/analize-medicale', [MedicalTestCategoryController::class, 'index'])->name('analize-medicale.all');
Route::get('/analize-medicale.html', [MedicalTestCategoryController::class, 'index'])->name('analize-medicale.all');
Route::get('/analize-medicale/{medicalTestCategory:slug}.html', [MedicalTestCategoryController::class, 'show'])->name('analize-medicale.category');

Route::get('/analize-medicale-explicate/{medicalTest:slug}.html', [MedicalTestController::class, 'show'])->name('analize-medicale.show');

require __DIR__.'/dashboard.php';
require __DIR__.'/auth.php';
require __DIR__.'/learn-about-redis.php';
