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


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
