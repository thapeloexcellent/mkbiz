<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleSearchController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('about', function () {
    return view('about');
})->name('about');


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/search', [GoogleSearchController::class, 'search']);
Route::get('/results', [GoogleSearchController::class, 'results']);
Route::get('/search-businesses', [GoogleSearchController::class, 'searching'])->name('search.businesses');
Route::get('/get-place-details', [GoogleSearchController::class, 'getPlaceDetails']);

require __DIR__.'/auth.php';
