<?php

use App\Http\Controllers\webController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\BookController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    
    Route::get('books', [App\Http\Controllers\BookController::class, 'index'])->name('books.index');
    Route::get('bookCreate', [App\Http\Controllers\BookController::class, 'create'])->name('books.create');
    Route::get('bookEdit/{id}', [App\Http\Controllers\BookController::class, 'edit'])->name("books.edit");
    Route::get('bookDelete/{id}', [App\Http\Controllers\BookController::class, 'delete'])->name("books.delete");
    Route::post('bookCreate', [App\Http\Controllers\BookController::class, 'createBook']);
    Route::post('bookEdit', [App\Http\Controllers\BookController::class, 'editBook']);
    
    Route::get('genres', [App\Http\Controllers\GenreController::class, "index"])->name('genres.index');
    Route::get('genreCreate', [App\Http\Controllers\GenreController::class, 'create'])->name('genres.create');
    Route::get('genreEdit/{id}', [App\Http\Controllers\GenreController::class, 'edit'])->name("genres.edit");
    Route::get('genreDelete/{id}', [App\Http\Controllers\GenreController::class, 'delete'])->name("genres.delete");
    Route::post('genreCreate', [App\Http\Controllers\GenreController::class, 'createGenre']);
    Route::post('genreEdit', [App\Http\Controllers\GenreController::class, 'editGenre']);

    Route::get('authors', [App\Http\Controllers\AuthorController::class, "index"])->name('authors.index');
    Route::get('authorCreate', [App\Http\Controllers\AuthorController::class, 'create'])->name('authors.create');
    Route::get('authorEdit/{id}', [App\Http\Controllers\AuthorController::class, 'edit'])->name("authors.edit");
    Route::get('authorDelete/{id}', [App\Http\Controllers\AuthorController::class, 'delete'])->name("authors.delete");
    Route::post('authorCreate', [App\Http\Controllers\AuthorController::class, 'createAuthor']);
    Route::post('authorEdit', [App\Http\Controllers\AuthorController::class, 'editAuthor']);

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
