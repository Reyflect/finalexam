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
use App\Http\Controllers\OrdersController;

Route::get('/orders', [OrdersController::class, 'showOrderPage'])->middleware('role:Superadmin');




Route::get('/login', [UserController::class, 'checkSession']);
Route::get('/dashboard', [UserController::class, 'checkSession']);
Route::get('/', [UserController::class, 'checkSession']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/loginAuthentication', [UserController::class, 'login']);
Route::get('/getusers', [UserController::class, 'viewUserId']);

Route::get('/getDistinctCategories', [CategoryController::class, 'categories']);
Route::get('/category', [CategoryController::class, 'categoryPage']);
Route::get('/createcategory', [CategoryController::class, 'categoryForm']);
Route::get('/editcategory/{id}', [CategoryController::class, 'categoryForm2']);
Route::post('/category/add', [CategoryController::class, 'addCategory']);
Route::post('/category/edit/{id}', [CategoryController::class, 'editCategory']);
Route::delete('/category/delete/{id}', [CategoryController::class, 'deleteCategory']);



Route::get('/Cart', [CartItemController::class, 'getCartItems']);
Route::get('/checkout', [CartItemController::class, 'getCartItems'])->defaults('content', 'checkout')->name('checkout');
Route::get('/items', [CartItemController::class, 'cartContents']);
Route::get('/itemsjson', [CartItemController::class, 'getCartItemsJSON']);
Route::get('/totalItems', [CartItemController::class, 'totalCart']);



Route::get('/getOrders', [OrdersController::class, 'getOrders']);


Route::get('/createproduct', function () {
    return Inertia::render('Dashboard', ['content' => 'addproduct']);
});

Route::get('/success',  [CartItemController::class, 'success']);

Route::get('/successpage', function () {
    return Inertia::render('Dashboard', ['content' => 'success']);
})->name('successpage');

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
