<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CategoryProductController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DiningController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\QueueController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\UserController;
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
Route::resource('dining', DiningController::class);
Route::resource('product', ProductController::class);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::prefix('transaction')->group(function () {
        Route::resource('/', TransactionController::class);
        Route::get('/invoice/{uuid}', [TransactionController::class, 'invoice']);
        Route::post('/cancel/{uuid}', [TransactionController::class, 'cancel']);
        Route::post('/rollback/{uuid}', [TransactionController::class, 'rollback']);
        Route::post('/checkout/{uuid}', [TransactionController::class, 'checkout']);
        Route::get('/order/{id}', [TransactionController::class, 'detailCart']);
        Route::put('/cart/{id}', [TransactionController::class, 'submitCart']);
        Route::get('/cart-delete/{id}', [TransactionController::class, 'deleteCart']);
    });

    Route::resource('user', UserController::class);

    Route::get('/queue',[QueueController::class, 'index']);
    Route::post('/queue/proses/{id}',[QueueController::class, 'proses']);
    Route::post('/queue/serve/{id}',[QueueController::class, 'serve']);

    Route::prefix('report')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::post('/export-transaction',[ReportController::class, 'exportTransaction']);
    });
});
