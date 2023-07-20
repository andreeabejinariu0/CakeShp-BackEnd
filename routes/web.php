<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

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


//rute pentru autentificare
Route::post('/login', [App\Http\Controllers\API\UserController::class, 'login']);
Route::post('/register', [App\Http\Controllers\API\UserController::class, 'register']);

//rute pentru users
Route::middleware(['auth:api'])->group(function () {

    // List users
    Route::middleware(['scope:admin'])->get('/users', [App\Http\Controllers\API\UserController::class, 'getUsers'] );

    // Add/Edit User
    Route::middleware(['scope:admin'])->post('/user',[App\Http\Controllers\API\UserController::class, 'addUser'] );

    Route::middleware(['scope:admin'])->post('/user/{userId}', [App\Http\Controllers\API\UserController::class, 'updateUser'] );

    // Delete User
    Route::middleware(['scope:admin'])->delete('/user/{userId}', [App\Http\Controllers\API\UserController::class, 'deleteUser']);

    // Admin User
    Route::middleware(['scope:admin'])->post('/admin/{userId}',[App\Http\Controllers\API\UserController::class, 'adminUser'] );
});


//rute pentru orders
Route::middleware(['auth:api'])->group(function () {

Route::middleware(['scope:admin,basic'])->post('/send-order', [App\Http\Controllers\OrderController::class, 'sendOrder']);
Route::middleware(['scope:admin,basic'])->get('/getOrderProducts/{order_id}', [App\Http\Controllers\OrderController::class, 'getProductsByOrder']);
Route::middleware(['scope:admin,basic'])->get('/getOrders', [App\Http\Controllers\OrderController::class, 'getOrders']);
});


//rute pentru category
Route::get('/category', [App\Http\Controllers\CategoryController::class, 'getAllCategory']);

//rute speciale pentru category
Route::middleware(['auth:api'])->group(function () {

    // List category
   // Route::middleware(['scope:admin,basic'])->get('/category', [App\Http\Controllers\CategoryController::class, 'getAllCategory'] );

    // Add/Edit category
    Route::middleware(['scope:admin'])->post('/category',[App\Http\Controllers\CategoryController::class, 'addCategory'] );

    Route::middleware(['scope:admin'])->post('/category/{categoryId}', [App\Http\Controllers\CategoryController::class, 'updateCategory'] );

    // Delete category
    Route::middleware(['scope:admin'])->delete('/category/{categoryId}', [App\Http\Controllers\CategoryController::class, 'deleteCategory']);

});

//rute pentru products
Route::get('/products', [App\Http\Controllers\ProductController::class, 'getAllProduct']);
Route::get('/products/{category_id}', [App\Http\Controllers\ProductController::class, 'search']);
Route::get('/product/{id}', [App\Http\Controllers\ProductController::class, 'getOneProduct']);

//rute speciale pentru products
Route::middleware(['auth:api'])->group(function () {

    // List products
   // Route::middleware(['scope:admin,basic'])->get('/category', [App\Http\Controllers\CategoryController::class, 'getAllCategory'] );

    // Add/Edit products
    Route::middleware(['scope:admin'])->post('/produs',[App\Http\Controllers\ProductController::class, 'addProduct'] );

    Route::middleware(['scope:admin'])->post('/produs/{productId}', [App\Http\Controllers\ProductController::class, 'updateProduct'] );

    // Delete products
    Route::middleware(['scope:admin'])->delete('/produs/{productId}', [App\Http\Controllers\ProductController::class, 'deleteProduct']);

});


Route::middleware(['auth:api'])->group(function () {
    Route::middleware(['scope:admin'])->get('/role', function (Request $request) {

    $user = Auth::user();

    $userRole = $user->role()->first();

    return response()->json(['role' => $userRole->role]);
});
});

Route::get('/', function () {
    return view('welcome');
});
