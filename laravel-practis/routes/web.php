<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvExportHistoryController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'postCSV'])->name('users.CSV');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('companies', \App\Http\Controllers\CompanyController::class);
    Route::resource('companies.sections', \App\Http\Controllers\SectionController::class);
    Route::resource('sections.users', \App\Http\Controllers\SectionUserController::class)->only(['store', 'destroy']);

    Route::get('/csv-export-histories', [CsvExportHistoryController::class, 'index'])->name('csvExportHistories.index');
    Route::get('csv-export-history/download/{csv_export_history}', [CsvExportHistoryController::class, 'download'])->name('csv-export-history.download');

});

require __DIR__ . '/auth.php';
