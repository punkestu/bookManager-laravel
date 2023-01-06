<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;

use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
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
            $genres = Genre::all();
            return view('genres.index', compact("genres"));
      }

      public function delete($id)
      {
            $genre = Genre::find($id);
            if ($genre == null) {
                  return view("notfound");
            }

            $genre->delete();
            return redirect("genres");
      }

      public function edit($id)
      {
            $genre = Genre::find($id);
            if ($genre == null) {
                  return view("notfound");
            }

            $errors = [];
            return view('genres.create', compact("genre", "errors", "id"));
      }

      public function create()
      {
            $errors = [];
            return view('genres.create', compact("errors"));
      }

      public function editGenre(Request $request)
      {
            $validator = Validator::make($request->all(), [
                  "id" => "required"
            ]);

            if ($validator->fails()) {
                  $errors = $validator->messages()->get("*");
                  $genre = Genre::find($request->id);
                  if ($genre == null) {
                        return view("notfound");
                  }
                  $id = $request->id;
                  return view('genres.create', compact("genre", "errors", "id"));
            }

            $genre = Genre::find($request->id);
            if ($genre == null) {
                  return view("notfound");
            }


            $genre->update(["genre_name" => $request->genre_name]);

            return redirect("/genres");
      }

      public function createGenre(Request $request)
      {
            $validator = Validator::make($request->all(), [
                  "genre_name" => "required"
            ]);

            if ($validator->fails()) {
                  $errors = $validator->messages()->get("*");
                  return view('genres.create', compact("errors"));
            }

            $genre = new Genre([
                  "genre_name" => $request->genre_name,
            ]);
            $genre->save();

            return redirect("/genres");
      }
}
