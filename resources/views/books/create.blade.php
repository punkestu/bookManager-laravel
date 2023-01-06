@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __(isset($id) ? 'Edit Book' : 'Create Book') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <form action={{ isset($id) ? url('bookEdit') : url('bookCreate') }} method="POST" class="container">
            @csrf
            @isset($book)
                <input type="hidden" name="id" value={{ $id }}>
            @endisset

            <label for="Name">Name</label>
            <div class="input-group w-75">
                <input type="text" class="form-control" name="book_name" placeholder="Name" aria-label="Name"
                    aria-describedby="basic-addon1" id="name" value="{{ isset($book) ? $book->book_name : '' }}"">
            </div>
            <div class="mb-3 ">
                <p class="text-danger">{{ isset($errors['book_name']) ? $errors['book_name'][0] : '' }}</p>
            </div>

            <label for="Price">Price</label>
            <div class="input-group w-75">
                <input type="number" class="form-control" name="book_price" placeholder="Price" aria-label="Price"
                    aria-describedby="basic-addon1" id="price" value={{ isset($book) ? $book->book_price : '' }}>
            </div>
            <div class="mb-3 ">
                <p class="text-danger">{{ isset($errors['book_price']) ? $errors['book_price'][0] : '' }}</p>
            </div>

            <label for="Author">Author</label>
            <div class="input-group w-75">
                <select class="custom-select" name="book_author" id="author">
                    <option disabled selected value>Choose Author</option>
                    @foreach ($authors as $author)
                        <option value={{ $author->id }}
                            @isset($book)
                                @if ($author->id == $book->book_author)
                                    selected="selected"
                                @endif    
                            @endisset
                        >
                            {{ $author->author_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 ">
                <p class="text-danger">{{ isset($errors['book_author']) ? $errors['book_author'][0] : '' }}</p>
            </div>

            <label for="Genre">Genre</label>
            <div class="input-group mb-3 w-75">
                <select class="custom-select mr-1" id="genre1" name="genres[]">
                    <option selected value>-- Empty --</option>
                    @foreach ($genres as $genre)
                        <option value={{ $genre->id }}
                            @isset($book)
                                @if ($book->has('genres')) 
                                    @if (count($book->genres) > 0 && $genre->id == $book->genres[0]->genre_id)
                                        selected="selected" 
                                    @endif
                                @endif
                            @endisset
                        >
                            {{ $genre->genre_name }}
                        </option>
                    @endforeach
                </select>
                <select class="custom-select mx-1" id="genre2" name="genres[]">
                    <option selected value>-- Empty --</option>
                    @foreach ($genres as $genre)
                        <option value={{ $genre->id }}
                            @isset($book)
                                @if ($book->has('genres')) 
                                    @if (count($book->genres) > 1 && $genre->id == $book->genres[1]->genre_id)
                                        selected="selected" 
                                    @endif
                                @endif
                            @endisset
                        >
                            {{ $genre->genre_name }}
                        </option>
                    @endforeach
                </select>
                <select class="custom-select mx-1" id="genre3" name="genres[]">
                    <option selected value>-- Empty --</option>
                    @foreach ($genres as $genre)
                        <option value={{ $genre->id }}
                            @isset($book)
                                @if ($book->has('genres'))
                                    @if (count($book->genres) > 2 && $genre->id == $book->genres[2]->genre_id)
                                        selected="selected" 
                                    @endif
                                @endif
                            @endisset
                        >
                            {{ $genre->genre_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex align-items-stretch mt-4">
                <button type="submit" class="btn btn-primary">
                    @if (isset($id))
                        Update
                    @else
                        Create
                    @endif
                </button>
                <a href={{ route('books.index') }} class="ml-2 btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
