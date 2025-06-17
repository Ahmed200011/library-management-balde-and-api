<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function store(Book $book)
    {

        if ($book->status !== 'available') {
            return back()->with('error', 'الكتاب غير متاح حاليًا.');
        }
        Borrowing::create([
            'book_id' => $book->id,
            'user_id' => Auth::id(),
            'borrowed_at' => now(),
        ]);
        $book->update(['status' => 'borrowed']);

        return redirect()->route('books.index')->with('success', 'تم استعارة الكتاب بنجاح.');
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

        return redirect()->route('books.index')->with('success', 'تم إرجاع الكتاب بنجاح.');
    }
}
