<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;

class webController extends Controller
{
      public function books(Request $request)
      {
            if (isset($request->genres) && count($request->genres) > 0) {
                  $books = new BookCollection(
                        Book::with("author", "genres.genre")
                              ->whereHas(
                                    "genres.genre",
                                    function ($query) use ($request) {
                                          return $query->whereIn("genre_name", $request->genres);
                                    }
                              )
                              ->get()
                  );
                  return view("books.index", $books);
            } else {
                  $books = new BookCollection(Book::with("author", "genres.genre")->get());
                  return view("books.index", compact("books"));
            }
      }

      public function get($id)
      {
            $book = Book::with("author", "genres.genre")->find($id);
            if ($book == null) {
                  return $this->sendError(["id" => "Book with id $id is not found"], 404);
            }
            $data = new BookResource($book);
            return $this->sendSuccess($data);
      }

      public function create(Request $request)
      {
            $validator = Validator::make($request->all(), [
                  "book_author" => "required",
                  "book_name" => "required",
                  "book_price" => "required|integer"
            ]);

            if ($validator->fails()) {
                  return $this->sendError($validator->errors());
            }

            $msg = [];
            DB::beginTransaction();

            // validate Author name in authors table
            $author = Author::where("author_name", $request->book_author)->get();
            if (count($author) === 0) {
                  DB::rollBack();
                  return $this->sendError(
                        ["book_author" => "Author \"$request->book_author\" is not found"],
                        404
                  );
            }

            // try create Book record in books table
            $book = new Book([
                  "book_name" => $request->book_name,
                  "book_author" => $author[0]->id,
                  "book_price" => $request->book_price
            ]);
            $book->save();

            // validate the number of genre
            if (count($request->genres) > 3) {
                  $msg["genres"] = "Genre are excessive ( max 3 you give " . count($request->genres) . " )";
            }

            foreach ($request->genres as $i => $genre) {
                  if ($i > 3) {
                        break;
                  }
                  $genre_name = Genre::where("genre_name", $genre)->get();

                  // validate Genre name in genres table
                  if (count($genre_name) === 0) {
                        DB::rollBack();
                        return $this->sendError(
                              ["genres[$i]" => "Genre \"$genre\" is not found"],
                              404
                        );
                  } else {
                        $book->genres()->create([
                              "genre_id" => $genre_name[0]->id
                        ]);
                  }
            }

            DB::commit();
            return $this->sendSuccess(["book_id" => $book->id], count($msg) == 0 ? null : $msg);
      }

      public function update(Request $request)
      {
            $validator = Validator::make($request->all(), [
                  "id" => "required",
                  "updated.genres.*.id" => "required|integer",
                  "updated.genres.*.genre_name" => "required|string",
                  "updated.book_price" => "integer"
            ]);
            if ($validator->fails()) {
                  return $this->sendError($validator->errors());
            }

            $book = Book::find($request->id);
            if ($book == null) {
                  return $this->sendError(["id" => "book with id $request->id is not found"], 404);
            }

            $request = new Request($request->updated);
            $updated = $request->only("book_name", "book_price", "author_name");
            $genreUD = $request->genres ? $request->genres : [];

            $genres = Book::find($book->id)->genres;
            DB::beginTransaction();

            foreach ($genreUD as $i => $genre) {
                  $genre_name = Genre::where("genre_name", $genre["genre_name"])->get();
                  if (count($genre_name) === 0) {
                        DB::rollBack();
                        return $this->sendError(["genres[$i]" => "Genre \"" . $genre["genre_name"] . "\" is not found"], 404);
                  }
                  if ($genre["id"] < 0) {
                        if (count($genres) < 3) {
                              if ($genres->where("genre_id", $genre_name[0]->id)->count() > 0) {
                                    DB::rollBack();
                                    return $this->sendError(["genres[$i]" => "Genre \"" . $genre["genre_name"] . "\" duplicated"], 400);
                              }
                              Book::find($book->id)->genres()->create([
                                    "genre_id" => $genre_name[0]->id
                              ]);
                        } else {
                              DB::rollBack();
                              return $this->sendError(["genres" => "Genre \"" . $genre["genre_name"] . "\" cannot be added, Genre is full [3/3]"]);
                        }
                  } else {
                        if ($genres->where("genre_id", $genre_name[0]->id)->count() > 0 && $genres[$genre["id"]]->genre_id != $genre_name[0]->id) {
                              DB::rollBack();
                              return $this->sendError(["genres[$i]" => "Genre \"" . $genre["genre_name"] . "\" duplicated"], 400);
                        }
                        $genres[$genre["id"]]->update(["genre_id" => $genre_name[0]->id]);
                  }
            }

            $book->update($updated);
            DB::commit();

            return $this->sendSuccess([
                  "book_id" => $book->id
            ]);
      }

      function delete(Request $request)
      {
            $book = Book::find($request->id);
            if ($book == null) {
                  return $this->sendError(["id" => "book with id $request->id is not found"], 404);
            }
            $book->delete();
            return $this->sendSuccess([
                  "book_id" => $request->id
            ]);
      }
}
