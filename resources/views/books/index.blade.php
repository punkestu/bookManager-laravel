@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Books') }}</h1>
                    <a href={{ route('books.create') }} class="btn btn-primary mt-2">Create</a>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <form action={{ url('books') }} class="mb-2" id="form-id">
                <div class="d-flex flex-wrap">
                    @foreach ($genres as $genre)
                        <div class="mx-2 my-1">
                            <input onclick="document.getElementById('form-id').submit();" class="d-none checkbox"
                                type="checkbox" name="genres[]" id={{ $genre->id }} value={{ $genre->id }}
                                @isset($filter)
                                    @if (in_array($genre->id, $filter))
                                @checked(true)
                        @endif
                    @endisset>
                            <label class="card p-2" for={{ $genre->id }}>{{ $genre->genre_name }}</label>
                        </div>
                    @endforeach
                </div>
            </form>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Author</th>
                                        <th scope="col" class="text-center">Genre</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($books as $book)
                                        <tr>
                                            <td class="align-middle">{{ $book->book_name }}</td>
                                            <td class="align-middle">{{ $book->book_price }}</td>
                                            <td class="align-middle">{{ $book->author->author_name }}</td>
                                            <td class="align-middle" style="width: 35%">
                                                <div class="d-flex flex-wrap justify-content-center">
                                                    @foreach ($book->genres as $genre)
                                                        <div class="mx-2 my-2 p-2 bg-secondary rounded">
                                                            {{ $genre->genre->genre_name }}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex flex-column">
                                                    <a href={{ url('bookEdit', ['id' => $book->id]) }}
                                                        class="btn btn-warning mb-2">Edit</a>
                                                    <a href={{ url('bookDelete', ['id' => $book->id]) }}
                                                        class="btn btn-danger">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection
