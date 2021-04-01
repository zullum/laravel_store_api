<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logoutApi']);
Route::get('login_required', [UserController::class, 'login_required']);

Route::group(['middleware' => ['auth:api', 'role:manager,admin']], function(){
    Route::apiResource('store', StoreController::class);
    Route::apiResource('product', ProductController::class);
    Route::post('schedule-update/{product}', [ProductController::class, 'scheduleUpdate']);
});

Route::group(['middleware' => ['auth:api', 'role:customer']], function(){
    Route::post('order', [OrderController::class, 'store']);
    Route::put('order/{order}', [OrderController::class, 'update']);
});

Route::group(['middleware' => ['auth:api', 'role:admin,manager,customer']], function(){
    Route::get('order', [OrderController::class, 'index']);
    Route::get('order/{order}', [OrderController::class, 'show']);
});

Route::group(['middleware' => ['auth:api', 'role:admin']], function(){
    Route::apiResource('user', UserController::class);
    Route::delete('order/{order}', [OrderController::class, 'destroy']);
});
