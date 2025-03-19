<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;

Route::post('/register', [AuthController::class, "register"])->name('register');
Route::post('/login', [AuthController::class, "login"])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    // Kullanıcı İşlemleri
    Route::get('/users', [UserController::class, 'users']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);

    // Rol İşlemleri
    Route::get('/listRole', [RoleController::class, 'listRole']);

    // Kategori İşlemleri
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::post('/blogs', [BlogController::class, 'store']);
    Route::get('/blogs/{idOrSlug}', [BlogController::class, 'show']);
    Route::post('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/delete/{id}', [BlogController::class, 'destroy']);

    Route::post('/blogs/{idOrSlug}/comments', [CommentController::class, 'store']);
    Route::post('/blogs/comments/{commentId}', [CommentController::class, 'destroy']);

    //Yorum route ları
    Route::post('comment/{commentId}/approve', [CommentController::class, 'approve']);
    Route::post('comment/{commentId}/reject', [CommentController::class, 'reject']);
    Route::post('comment/{commentId}/pending', [CommentController::class, 'pending']);
    Route::get('comments/filter/{status}', [CommentController::class, 'filterComments']);
});

//İlerde proje token koyularak sadece projelerin cekmesi için middleware koyulabilir
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{blogId}/comments', [CommentController::class, 'index']);
