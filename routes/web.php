<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::redirect('dashboard', 'home');
    Route::get('home', [HomeController::class, 'index'])->name('home');

    Route::put('users/update-user', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/delete-user', [UserController::class, 'destroy'])->name('users.destroy');

    Route::put('posts/update-post', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/delete-post', [PostController::class, 'destroy'])->name('posts.destroy');
    
    Route::resource('users', UserController::class)->except(['create', 'update', 'edit', 'destroy']);
    Route::resource('posts', PostController::class)->except(['create', 'update', 'edit', 'destroy']);
    Route::get('posts/{post}/view', [PostController::class, 'viewPost'])->name('posts.viewPost');
    Route::post('/attachment', [PostController::class, 'uploadAttachment'])->name('post.attachment.upload');
    Route::delete('/attachment/{attachment}', [PostController::class, 'removeAttachment'])->name('post.attachment.remove');



    Route::get('users/{user}/roles', [UserController::class, 'getRoles'])->name('users.getUserRoles');
    Route::put('users/update-role', [UserController::class, 'updateUserRoles'])->name('users.updateUserRoles');
    Route::put('users/update-password', [UserController::class, 'updateUserPassword'])->name('users.updateUserPassword');
});

Route::middleware('auth')->group(function(){
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';