<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CategoryProductController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/auth/verify', [AuthController::class, 'verify']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::resource('category', CategoryProductController::class);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
});
