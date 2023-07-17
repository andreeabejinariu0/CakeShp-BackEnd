<?php

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

Route::get('/products', [App\Http\Controllers\ProductController::class, 'getAllProduct']);
Route::get('/products/{category_id}', [App\Http\Controllers\ProductController::class, 'search']);
Route::get('/product/{id}', [App\Http\Controllers\ProductController::class, 'getOneProduct']);

Route::get('/category', [App\Http\Controllers\CategoryController::class, 'getAllCategory']);


Route::post('/send-order', [App\Http\Controllers\OrderController::class, 'sendOrder']);
Route::get('/getOrderProducts/{order_id}', [App\Http\Controllers\OrderController::class, 'getProductsByOrder']);
Route::get('/getOrders/{user_id}', [App\Http\Controllers\OrderController::class, 'getOrders']);


Route::post('/login', [App\Http\Controllers\API\UserController::class, 'login']);
Route::post('/register', [App\Http\Controllers\API\UserController::class, 'register']);


Route::get('/', function () {
    return view('welcome');
});
