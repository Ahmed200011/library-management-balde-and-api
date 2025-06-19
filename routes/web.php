<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {

    Route::resource('books', BookController::class);
    Route::post('books/{book}/borrow', [BorrowingController::class, 'store'])->name('borrow.store');
    Route::post('books/{book}/return', [BorrowingController::class, 'return'])->name('borrow.return');
    Route::get('mybooks', [UserController::class, 'myBooks'])->name('myBooks');
    // Route::post('books', BookController::class);

});

require __DIR__ . '/auth.php';
