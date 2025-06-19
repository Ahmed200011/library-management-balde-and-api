<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function myBooks()
    {
        $books = Auth::user()->borrowings()->with('book')->get()->pluck('book');
        return ApiResponse::sendResponse(200, 'Books retrieved successfully', BookResource::collection($books));
    }
}
