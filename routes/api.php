<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;

Route::post('/register', [AuthController::class, "register"])->name('register');
Route::post('/login', [AuthController::class, "login"])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'users']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);


    //Role
    Route::get('/listRole', [RoleController::class, 'listRole']);

    //Category
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);

    //Blog
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::get('/blogs/{idOrSlug}', [BlogController::class, 'show']);
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
});
