<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\ProfessionalController;
use App\Http\Controllers\Professional\DashboardController;
use App\Http\Controllers\Professional\ProductController;
use App\Http\Controllers\Professional\ServiceController;

// Route for Both (Auth and Guest)
Route::get('/home',[RouteController::class,'landing']);
Route::get('/services',[RouteController::class,'services']);
Route::post('/search',[RouteController::class,'search']);
Route::get('/products',[RouteController::class,'products']);
Route::get('/product/{id}',[RouteController::class,'product']);
Route::get('/service/{id}',[RouteController::class,'service']);

// User Authentication and Profile Routes
Route::prefix('user')->group(function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('verify-email', [UserController::class, 'verify']);
    Route::middleware(['auth:sanctum', 'auth:user'])->group(function () {
        Route::get('edit', [UserController::class, 'edit']);
        Route::post('update', [UserController::class, 'update']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::get('reservations', [ReservationController::class, 'index']);
        Route::get('orders', [OrderController::class, 'index']);
    });
});
// Reservation Routes
Route::middleware(['auth:sanctum', 'auth:user'])->group(function () {
    Route::post('reservation/create/{id}', [ReservationController::class, 'store']);
});
// Order Routes
Route::middleware(['auth:sanctum', 'auth:user'])->group(function () {
    Route::post('order/create/{id}', [OrderController::class, 'store']);
});
// Comment Routes
Route::middleware(['auth:sanctum', 'auth:user'])->group(function () {
    Route::post('product/comment/add/{id}', [CommentController::class, 'add_product']);
    Route::post('service/comment/add/{id}', [CommentController::class, 'add_service']);
});


// Professional Authentication and Profile Routes
Route::prefix('professional')->group(function () {
    Route::post('register', [ProfessionalController::class, 'register']);
    Route::post('login', [ProfessionalController::class, 'login']);
    Route::post('verify-email', [ProfessionalController::class, 'verify']);
    Route::post('reset-password', [ProfessionalController::class, 'reset']);

    Route::middleware(['auth:sanctum', 'auth:professional'])->group(function () {
        Route::post('logout', [ProfessionalController::class, 'logout']);
        Route::post('update-profile', [ProfessionalController::class, 'update']);
        Route::get('/dashboard',[DashboardController::class,'index']);

    });
});
// Service Routes
Route::prefix('professional/services')->middleware(['auth:sanctum', 'auth:professional'])->group(function () {
    Route::get('index', [ServiceController::class, 'index']);
    Route::post('store', [ServiceController::class, 'store']);
    Route::get('show/{id}', [ServiceController::class, 'show']);
    Route::get('edit/{id}', [ServiceController::class, 'edit']);
    Route::post('update/{id}', [ServiceController::class, 'update']);
    Route::delete('destroy/{id}', [ServiceController::class, 'destroy']);
    Route::delete('destroy-image/{id}', [ServiceController::class, 'destroyImage']);
});
// Product Routes
Route::prefix('professional/products')->middleware(['auth:sanctum', 'auth:professional'])->group(function () {
    Route::get('all', [ProductController::class, 'index']);
    Route::post('create', [ProductController::class, 'store']);
    Route::get('product/{id}', [ProductController::class, 'show']);
    Route::get('edit/{id}', [ProductController::class, 'edit']);
    Route::post('update/{id}', [ProductController::class, 'update']);
    Route::delete('delete/{id}', [ProductController::class, 'destroy']);
    Route::delete('destroy-image/{id}', [ProductController::class, 'destroyImage']);
});
// Reservation Routes
Route::prefix('professional/reservations')->middleware(['auth:sanctum', 'auth:professional'])->group(function () {
    Route::get('/', [ReservationController::class, 'list']);
    Route::post('/change', [ReservationController::class, 'change']);
});
// Order Routes
Route::prefix('professional/orders')->middleware(['auth:sanctum', 'auth:professional'])->group(function () {
    Route::get('/', [OrderController::class, 'list']);
    Route::post('change', [OrderController::class, 'change']);
});
