<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use Illuminate\Validation\Rule;

class apiController extends Controller
{
    public function getAll(Request $request)
    {
        if (isset($request->genres) && count($request->genres) > 0) {
            $data = new BookCollection(
                Book::with("author", "genres.genre")
                    ->whereHas(
                        "genres.genre",
                        function ($query) use ($request) {
                            return $query->whereIn("genre_name", $request->genres);
                        }
                    )
                    ->get()
            );
            return $this->sendSuccess($data);
        } else {
            $data = new BookCollection(Book::with("author", "genres.genre")->get());
            return $this->sendSuccess($data);
        }
    }

    public function get($id)
    {
        $validator = Validator::make(["id" => $id], [
            "id" => "required|exists:books,id"
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $book = Book::with("author", "genres.genre")->find($id);
        $data = new BookResource($book);
        return $this->sendSuccess($data);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "book_author" => "required|exists:authors,author_name",
            "book_name" => "required|unique:books,book_name",
            "book_price" => "required|integer",
            "genres" => "array|max:3",
            "genres.*" => "exists:genres,genre_name"
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $msg = [];

        $author = Author::where("author_name", $request->book_author)->get();
        $book = new Book([
            "book_name" => $request->book_name,
            "book_author" => $author[0]->id,
            "book_price" => $request->book_price
        ]);
        $book->save();

        foreach ($request->genres as $i => $genre) {
            $genre_name = Genre::where("genre_name", $genre)->get();

            $book->genres()->create([
                "genre_id" => $genre_name[0]->id
            ]);
        }

        return $this->sendSuccess(["book_id" => $book->id], count($msg) == 0 ? null : $msg);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required",
            "genres.*.action" => ["required", Rule::in(["add", "delete", "update"])],
            "genres.*.index" => "required|integer",
            "genres.*.genre_name" => [
                "required", "exists:genres,genre_name"
            ],
            "book_author" => "exists:authors,author_name",
            "book_price" => "integer"
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $book = Book::find($request->id);
        $book_genres = $book->genres;

        $updated = $request->only("book_name", "book_price", "author_name");
        $genres = $request->genres ? $request->genres : [];

        foreach ($genres as $i => $genre) {
            $genre_name = Genre::where("genre_name", $genre["genre_name"])->get();
            if ($genre["action"] == "add") {
                if (count($book_genres) < 3) {
                    $book->genres()->create(["genre_id" => $genre_name[0]->id]);
                }
            }
            if ($genre["action"] == "update") {
                $book_genres[$genre["index"]]->update(["genre_id" => $genre_name[0]->id]);
            }
            if ($genre["action"] == "delete") {
                $book_genres[$genre["index"]]->delete();
            }
        }

        $book->update($updated);

        return $this->sendSuccess([
            "book_id" => $book->id
        ]);
    }

    function delete($id)
    {
        $validator = Validator::make(["id" => $id], [
            "id" => "required|exists:books,id"
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $book = Book::find($id);
        $book->delete();
        return $this->sendSuccess([
            "book_id" => $id
        ]);
    }
}
