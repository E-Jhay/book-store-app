<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index']);
Route::get('/books/export', [BookController::class, 'export'])->name('books.export');
Route::resource('/books', BookController::class);

