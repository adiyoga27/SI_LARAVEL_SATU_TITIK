<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('registration', [AuthController::class, 'registration']);
Route::post('login', [AuthController::class, 'login']);

// Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('book-table' , [OrderController::class, 'bookTable']);
    Route::get('category' , [ProductController::class, 'category']);
    Route::get('product' , [ProductController::class, 'product']);
    Route::get('product/{category_id}' , [ProductController::class, 'productByCategory']);
    Route::post('reservasi' , [OrderController::class, 'reservasi']);
    Route::post('add-cart' , [OrderController::class, 'addCart']);
    Route::delete('delete-cart/{id}' , [OrderController::class, 'deleteCart']);
    Route::get('order/{uuid}' , [OrderController::class, 'order']);
    Route::post('checkout/{uuid}' , [OrderController::class, 'checkout']);
    Route::get('dinning-table', [OrderController::class, 'dinningTable']);
// });

