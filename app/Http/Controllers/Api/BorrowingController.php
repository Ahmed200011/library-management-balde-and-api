<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function store(Book $book)
    {

        if ($book->status !== 'available') {
            return ApiResponse::sendResponse(400, 'the book is not available', null);
        }
        Borrowing::create([
            'book_id' => $book->id,
            'user_id' => Auth::id(),
            'borrowed_at' => now(),
        ]);
        $book->update(['status' => 'borrowed']);
        return ApiResponse::sendResponse(200, 'the book is borrowed successfully', new BookResource($book));
    }
    public function return(Book $book)
    {
        $borrowing = Borrowing::where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->first();
        // $borrowing->update(['returned_at' => now()]);
        $borrowing->delete();
        $book->update(['status' => 'available']);
        return ApiResponse::sendResponse(200, 'the book is returned successfully', new BookResource($book));
    }
}
