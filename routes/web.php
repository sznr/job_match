<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\JobOfferController;
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

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');

Route::get('/', [JobOfferController::class, 'index'])
    ->name('root');

Route::resource('job_offers', JobOfferController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth:companies');

Route::resource('job_offers', JobOfferController::class)
    ->only(['show', 'index'])
    ->middleware('auth:companies,users');

Route::patch('/job_offers/{job_offer}/entries/{entry}/approval', [EntryController::class, 'approval'])
    ->name('job_offers.entries.approval')
    ->middleware(['auth:companies']);

Route::patch('/job_offers/{job_offer}/entries/{entry}/reject', [EntryController::class, 'reject'])
    ->name('job_offers.entries.reject')
    ->middleware(['auth:companies']);

Route::resource('job_offers.entries', EntryController::class)
    ->only(['store', 'destroy'])
    ->middleware(['auth:users']);

require __DIR__ . '/auth.php';