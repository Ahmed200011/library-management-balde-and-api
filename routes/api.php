<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('books', BookController::class);
    Route::get('user/books', [UserController::class, 'myBooks']);
    Route::post('books/{book}/borrow', [BorrowingController::class, 'store']);
    Route::post('books/{book}/return', [BorrowingController::class, 'return']);
});

// Route::post('/login', [AuthenticationController::class, 'login']);
// Route::get('/l', [BookController::class, 'index']);
