<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayMayaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('maya', [PayMayaController::class, 'postRequest']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/search', [ProductController::class, 'search']);

Route::post('cart/add', [CartItemController::class, 'add']);
Route::put('cart/update/{id}', [CartItemController::class, 'updateCartItem']);
Route::delete('cart/remove/{id}', [CartItemController::class, 'removeCartItem']);

Route::post('addnewproduct',  [ProductController::class, 'addNewProduct']);
Route::get('products/{product}', [ProductController::class, 'getProductById']);
Route::post('updateproduct/{product}', [ProductController::class, 'updateProduct']);
Route::delete('products/{product}', [ProductController::class, 'deleteProduct']);
