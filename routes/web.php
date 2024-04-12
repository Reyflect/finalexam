<?php

use Inertia\Inertia;
use App\Models\Category;
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
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;

Route::get('/login', [UserController::class, 'checkSession']);
Route::get('/dashboard', [UserController::class, 'checkSession']);
Route::get('/', [UserController::class, 'checkSession']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/loginAuthentication', [UserController::class, 'login']);
Route::get('/getusers', [UserController::class, 'viewUserId']);
Route::get('/getDistinctCategories', [CategoryController::class, 'categories']);


Route::get('/Cart', [CartItemController::class, 'getCartItems']);
Route::get('/checkout', [CartItemController::class, 'getCartItems'])->defaults('content', 'checkout');
Route::get('/items', [CartItemController::class, 'cartContents']);
Route::get('/itemsjson', [CartItemController::class, 'getCartItemsJSON']);



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


Route::get(
    '/videos',
    function () {

        $videoName = request('video', 'hedgehog');
        return Inertia::render('Dashboard', ['content' => 'video', 'video' => $videoName]);
    }
);
