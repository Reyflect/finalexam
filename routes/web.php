<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


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
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;


Route::get('/login', [UserController::class, 'checkSession']);
Route::get('/dashboard', [UserController::class, 'checkSession']);
Route::get('/', [UserController::class, 'checkSession']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/loginAuthentication', [UserController::class, 'login']);


Route::get('/api/products', [ProductController::class, 'index']);
Route::get('/getDistinctCategories', [ProductController::class, 'getDistinctCategories']);
Route::get('/api/products/search', [ProductController::class, 'search']);


Route::get('/Cart', [CartItemController::class, 'getCartItems']);
Route::get('/items', [CartItemController::class, 'getCartItemsJSON']);
Route::post('/api/cart/add', [CartItemController::class, 'addToCart']);
Route::put('/api/cart/update/{id}', [CartItemController::class, 'updateCartItem']);
Route::delete('/api/cart/remove/{id}', [CartItemController::class, 'removeCartItem']);
Route::get('/checkout', [CartItemController::class, 'getCartItemsCheckout']);

Route::get('/createproduct', function () {
    return Inertia::render('Dashboard', ['content' => 'addproduct']);
});

Route::get('/success',  [CartItemController::class, 'emptyCart']);
Route::get('/fail', function () {
    return Inertia::render('Dashboard', ['content' => 'fail']);
});



Route::get(
    '/editproduct/{product}',
    [ProductController::class, 'showEditProduct']
);

Route::post('/api/addnewproduct',  [ProductController::class, 'addNewProduct']);
Route::get('/api/products/{product}', [ProductController::class, 'getProductById']);
Route::post('/api/updateproduct/{product}', [ProductController::class, 'updateProduct']);

Route::delete('/api/products/{product}', [ProductController::class, 'deleteProduct']);

Route::get(
    '/videos',
    function () {

        $videoName = request('video', 'hedgehog');
        return Inertia::render('Dashboard', ['content' => 'video', 'video' => $videoName]);
    }
);
