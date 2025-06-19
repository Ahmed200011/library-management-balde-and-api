<?php

namespace App\Http\Controllers;

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
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:available,borrowed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image validation
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = $file('image')->getClientOriginalName();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image_name = uniqid() . $name;
            $image->resize(354,  220); // Adjust the height and width as needed
            $image->save(public_path('image/books/') . $image_name);
        }


        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category,
            'description' => $request->description,
            'status' => $request->status,
            'image' => isset($image_name) ? $image_name : null, // Save image name if exists
        ]);

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:available,borrowed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image validation
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = $file('image')->getClientOriginalName();
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


        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category,
            'description' => $request->description,
            'status' => $request->status,
            'image' => isset($image_name) ? $image_name : null, // Save image name if exists
        ]);
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // dd($book);
        if ($book->image) {
            $imagePath = public_path('image/book/' . $book->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $book->delete();
        // return response()->json(['message' => 'تم حذف الكتاب بنجاح']);


        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
