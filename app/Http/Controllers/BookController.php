<?php

namespace App\Http\Controllers;

use App\Exports\BooksExport;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Imports\BooksImport;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $sortBy = Request::input('sortBy', 'name');
        $sortDir = Request::input('sortDir', 'asc');

        $books = Book::with('author')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhereHas('author', function ($query) use ($search) {
                                $query->where('name', 'like', "%{$search}%");
                            });
                    });
                })
                ->when($sortBy == 'author', function ($query) use ($sortDir) {
                    $query->join('authors', 'books.author_id', '=', 'authors.id')
                          ->orderBy('authors.name', $sortDir)
                          ->select('books.*');
                })
                ->orderBy($sortBy, $sortDir)
                ->get()
                ->map(function($book) {
                    return [
                        'id' => $book->id,
                        'name' => $book->name,
                        'cover' => "/storage/{$book->cover}",
                        'author' => $book->author->name,
                    ];
                });

        
        $filters = Request::only(['search']);
        // dd($books);
        return inertia('Book/Index', compact('books', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authors = Author::select('id', 'name')->get();
        
        return inertia('Book/Create', compact('authors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        // dd($request->validated());
        $validatedData = $request->validated();

        if ($request->hasFile('cover')) {
            $filePath = $request->file('cover')->store('images', 'public');
            $validatedData['cover'] = $filePath;
        }
        $book = Book::create($validatedData);

        return redirect()->back()->with('success', 'Book added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $authors = Author::select('id', 'name')->get();
        
        return inertia('Book/Edit', compact('authors', 'book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('cover')) {
            $filePath = $request->file('cover')->store('images', 'public');
            $validatedData['cover'] = $filePath;
        }

        // check if cover is set will then delete the current cover
        if ($request->hasFile('cover')) {
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $filePath = $request->file('cover')->store('images', 'public');
            $validatedData['cover'] = $filePath;
        } else {
            $validatedData['cover'] = $book->cover;
        }
        $book->update($validatedData);

        return redirect()->back()->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return to_route('books.index')
            ->with('success', 'Book Deleted Successfully.');
    }

    public function export()
    {
        return Excel::download(new BooksExport, 'books.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(HttpRequest $request)
    {
        $request->validate([
            'books' => 'required|mimes:csv,txt',
        ]);

        Excel::import(new BooksImport, $request->file('books'));

        return redirect()->back()->with('success', 'Books imported successfully.');
    }

    public function importBooks()
    {
        return inertia('Book/ImportBooks');
    }
}
