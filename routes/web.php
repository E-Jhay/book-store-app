<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index']);
Route::get('/books/export', [BookController::class, 'export'])->name('books.export');
Route::post('/books/import', [BookController::class, 'import'])->name('books.import');
Route::get('/books/import-books', [BookController::class, 'importBooks'])->name('books.importBooks');
Route::resource('/books', BookController::class);

