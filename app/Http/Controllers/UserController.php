<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function myBooks()
    {
        $books = Auth::user()->borrowings->map(function ($borrowing) {
            return $borrowing->book;
        });
        // $books = Auth::user()->borrowings()->with('book')->get();
        $borrowedBooks = Auth::user()->borrowings;

        return view('books.my_books', compact('books', 'borrowedBooks'));
    }
}
