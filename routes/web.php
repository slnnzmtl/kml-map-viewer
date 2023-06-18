<?php

use App\Http\Controllers\ProfileController;
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

//Route::mediaLibrary();


Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/map/{forestry}', [\App\Http\Controllers\HomeController::class, 'map']);
Route::get('/list/{forestry}', [\App\Http\Controllers\HomeController::class, 'list']);

Route::get('/google-kml', [\App\Http\Controllers\HomeController::class, 'file']);


Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard')->group(function () {
   Route::redirect('/', '/dashboard/categories');

    Route::get('/categories', [\App\Http\Controllers\PointCategoryController::class, 'index'])->name('.categories');
    Route::post('/categories/add', [\App\Http\Controllers\PointCategoryController::class, 'add'])->name('.categories.add');
    Route::post('/categories/update/{category}', [\App\Http\Controllers\PointCategoryController::class, 'update'])->name('.categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\PointCategoryController::class, 'destroy'])->name('.categories.destroy');

    Route::get('/points', [\App\Http\Controllers\PointController::class, 'index'])->name('.points');
    Route::post('/points/add', [\App\Http\Controllers\PointController::class, 'add'])->name('.points.add');
    Route::post('/points/update/{point}', [\App\Http\Controllers\PointController::class, 'update'])->name('.points.update');
    Route::delete('/points/destroy/{point}', [\App\Http\Controllers\PointController::class, 'destroy'])->name('.points.destroy');
    Route::delete('/points/destroy/media/{point}/{media}', [\App\Http\Controllers\PointController::class, 'removePointMedia'])->name('.points.destroy.media');

    Route::get('/affiliates', [\App\Http\Controllers\AffiliateController::class, 'index'])->name('.affiliates');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
