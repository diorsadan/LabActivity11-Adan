<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// home page goes straight to the posts list
Route::redirect('/', '/posts');

// guest middleware = only for people not logged in
// name('login') matters, the auth middleware sends guests to this route
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
// auth middleware = must be logged in, else redirected to login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
// create must be above {id} or "create" gets treated as an id
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
// {post} matches the Post $post in the controller (route model binding)
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');
// {id} stays like Lab 10 because show() has to check is_published itself
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');
