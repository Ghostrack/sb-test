<?php

use App\Http\Controllers\Posts\PostController;
use App\Http\Controllers\Posts\PostVisibilityController;
use App\Http\Controllers\Posts\UserFavouritePostController;
use App\Http\Controllers\UserDashboard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Redirect::route('dashboard');
});

Route::get('/dashboard', UserDashboard::class)->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('post.destroy');

    Route::put('/posts/{id}/visibility', [PostVisibilityController::class, 'update'])->name('post.visibility.update');
});

Route::post('/posts/{id}/favourite', [UserFavouritePostController::class, 'store'])->name('post.favourite.store');
Route::delete('/posts/{id}/favourite', [UserFavouritePostController::class, 'destroy'])->name('post.favourite.delete');

require __DIR__ . '/auth.php';
