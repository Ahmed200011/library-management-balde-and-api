<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BookController extends Controller implements HasMiddleware

{
    public static function middleware(): array
    {
        return [
            'auth',

            new Middleware('role:admin', except: ['index']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        if ($books->isEmpty()) {
            return ApiResponse::sendResponse(404, 'No books found', []);
        }
        return ApiResponse::sendResponse(200, 'Books retrieved successfully', BookResource::collection($books));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image_name = uniqid() . $name;
            $image->resize(354,  220); // Adjust the height and width as needed
            $image->save(public_path('image/books/') . $image_name);
        }
        if ($data) {
            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'category' => $request->category,
                'description' => $request->description,
                'status' => $request->status,
                'image' => isset($image_name) ? $image_name : null, // Save image name if exists
            ]);
        }
        return ApiResponse::sendResponse(201, 'Book created successfully', new BookResource($book));
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name =$file->getClientOriginalName();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image_name = uniqid() . $name;
            $image->resize(354,  220); // Adjust the height and width as needed
            $image->save(public_path('image/books/') . $image_name);
        } else {
            $image_name = $book->image;
        }
        if ($book->image) {
            $imagePath = public_path('image/book/' . $book->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($data) {
            $book->update([
                'title' => $request->title,
                'author' => $request->author,
                'category' => $request->category,
                'description' => $request->description,
                'status' => $request->status,
                'image' => isset($image_name) ? $image_name : null, // Save image name if exists
            ]);
            return ApiResponse::sendResponse(201, 'Book updated successfully', new BookResource($book));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->image) {
            $imagePath = public_path('image/book/' . $book->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $book->delete();
        return ApiResponse::sendResponse(200, 'Book deleted successfully', null);
    }
}
