<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;

use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $books = [];
        if (isset($request->genres) && count($request->genres) > 0) {
            $books = new BookCollection(
                Book::with("author", "genres.genre")
                    ->whereHas(
                        "genres.genre",
                        function ($query) use ($request) {
                            return $query->whereIn("id", $request->genres);
                        }
                    )
                    ->get()
            );
        } else {
            $books = new BookCollection(Book::with("author", "genres.genre")->get());
        }
        $genres = Genre::all();
        $filter = $request->genres;
        return view('books.index', compact("books", "genres", "filter"));
    }

    public function delete($id)
    {
        $book = Book::find($id);
        if ($book == null) {
            return view("notfound");
        }

        $book->delete();
        return redirect("books");
    }

    public function edit($id)
    {
        $book = Book::find($id);
        if ($book == null) {
            return view("notfound");
        }

        $authors = Author::all();
        $genres = Genre::all();
        $errors = [];
        $book = new BookResource(Book::with("author", "genres")->find($id));
        return view('books.create', compact("authors", "genres", "errors", "id", "book"));
    }

    public function create()
    {
        $authors = Author::all();
        $genres = Genre::all();
        $errors = [];
        return view('books.create', compact("authors", "genres", "errors"));
    }

    public function editBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required",
            "book_price" => "integer"
        ]);

        if ($validator->fails()) {
            $authors = Author::all();
            $genres = Genre::all();
            $errors = $validator->messages()->get("*");
            $id = $request->id;
            $book = Book::find($id);
            if ($book == null) {
                return view("notfound");
            }
            return view('books.create', compact("authors", "genres", "errors", "id", "book"));
        }

        $book = Book::find($request->id);
        if ($book == null) {
            return view("notfound");
        }

        $updated = $request->only("book_name", "book_price", "author_name");
        $genreUD = $request->genres;

        $book_genres = Book::find($book->id)->genres;

        foreach ($genreUD as $i => $genre) {
            if ($genre == null) {
                if (count($book_genres) > $i) {
                    $book_genres[$i]->delete();
                }
            } else {
                if (count($book_genres) <= $i) {
                    $book->genres()->create(["genre_id" => $genre]);
                } else {
                    $book_genres[$i]->update(["genre_id" => $genre]);
                }
            }
        }
        $book->update($updated);

        return redirect("/books");
    }

    public function createBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "book_author" => "required|exists:authors,id",
            "book_name" => "required",
            "book_price" => "required|integer",
            "genres.*" => "integer|exists:genres,id"
        ]);

        if ($validator->fails()) {
            $authors = Author::all();
            $genres = Genre::all();
            $errors = $validator->messages()->get("*");
            return view('books.create', compact("authors", "genres", "errors"));
        }

        // try create Book record in books table
        $author = Author::find($request->book_author);
        $book = new Book([
            "book_name" => $request->book_name,
            "book_author" => $author->id,
            "book_price" => $request->book_price
        ]);
        $book->save();

        if ($request->genres != null) {
            foreach ($request->genres as $i => $genre) {
                $book->genres()->create([
                    "genre_id" => $genre
                ]);
            }
        }

        return redirect("/books");
    }
}
