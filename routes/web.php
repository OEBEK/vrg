<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/todos', [App\Http\Controllers\TodoController::class, 'index'])->middleware('auth')->name('todos.index');
Route::get('/todos/create', [App\Http\Controllers\TodoController::class, 'create'])->middleware('auth')->name('todos.create');
Route::post('/todos', [App\Http\Controllers\TodoController::class, 'store'])->middleware('auth')->name('todos.store');
Route::get('/todos/{todo}', [App\Http\Controllers\TodoController::class, 'show'])->middleware('auth')->name('todos.show');
Route::get('/todos/{todo}/edit', [App\Http\Controllers\TodoController::class, 'edit'])->middleware('auth')->name('todos.edit');
Route::put('/todos/{todo}', [App\Http\Controllers\TodoController::class, 'update'])->middleware('auth')->name('todos.update');
Route::delete('/todos/{todo}', [App\Http\Controllers\TodoController::class, 'destroy'])->middleware('auth')->name('todos.destroy');

Route::get('/api/todos', [App\Http\Controllers\TodoController::class, 'apiIndex'])->middleware('auth');
Route::get('/api/todos/{todo}', [App\Http\Controllers\TodoController::class, 'apiShow'])->middleware('auth');
Route::get('/api/categories', [App\Http\Controllers\TodoController::class, 'getCategories'])->middleware('auth');
Route::put('/api/todos/{todo}', [App\Http\Controllers\TodoController::class, 'update'])->middleware('auth');
Route::delete('/api/todos/{todo}', [App\Http\Controllers\TodoController::class, 'destroy'])->middleware('auth');
