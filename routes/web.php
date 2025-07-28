<?php

use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('/', 'welcome'); //just return view

Route::resource('/posts', PostController::class);
Route::get('/register', [RegisterUserController::class, 'register'])->name('register'); //definition of function for register
Route::post('/register', [RegisterUserController::class, 'store'])->name('register.store');
Route::get('/login', [LoginUserController::class, 'login'])->name('login');
Route::post('/login', [LoginUserController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginUserController::class, 'logout'])->name('logout');