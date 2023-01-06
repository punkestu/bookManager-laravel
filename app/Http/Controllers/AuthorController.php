<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;

use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
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
            $authors = Author::all();
            return view('authors.index', compact("authors"));
      }

      public function delete($id)
      {
            $author = Author::find($id);
            if ($author == null) {
                  return view("notfound");
            }

            $author->delete();
            return redirect("authors");
      }

      public function edit($id)
      {
            $author = Author::find($id);
            if ($author == null) {
                  return view("notfound");
            }

            $errors = [];
            return view('authors.create', compact("author", "errors", "id"));
      }

      public function create()
      {
            $errors = [];
            return view('authors.create', compact("errors"));
      }

      public function editAuthor(Request $request)
      {
            $validator = Validator::make($request->all(), [
                  "id" => "required"
            ]);

            if ($validator->fails()) {
                  $errors = $validator->messages()->get("*");
                  $author = Author::find($request->id);
                  if ($author == null) {
                        return view("notfound");
                  }
                  $id = $request->id;
                  return view('authors.create', compact("author", "errors", "id"));
            }

            $author = Author::find($request->id);
            if ($author == null) {
                  return view("notfound");
            }


            $author->update(["author_name" => $request->author_name]);

            return redirect("/authors");
      }

      public function createAuthor(Request $request)
      {
            $validator = Validator::make($request->all(), [
                  "author_name" => "required"
            ]);

            if ($validator->fails()) {
                  $errors = $validator->messages()->get("*");
                  return view('authors.create', compact("errors"));
            }

            $author = new Author([
                  "author_name" => $request->author_name,
            ]);
            $author->save();

            return redirect("/authors");
      }
}
