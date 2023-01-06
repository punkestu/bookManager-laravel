@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __(isset($id) ? 'Edit Author' : 'Create Author') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <form action={{ isset($id) ? url('authorEdit') : url('authorCreate') }} method="POST" class="container">
            @csrf
            @isset($author)
                <input type="hidden" name="id" value={{ $id }}>
            @endisset

            <label for="Name">Name</label>
            <div class="input-group w-75">
                <input type="text" class="form-control" name="author_name" placeholder="Name" aria-label="Name"
                    aria-describedby="basic-addon1" id="name" value="{{ isset($author) ? $author->author_name : '' }}"">
            </div>
            <div class="mb-3 ">
                <p class="text-danger">{{ isset($errors['author_name']) ? $errors['author_name'][0] : '' }}</p>
            </div>

            <div class="d-flex align-items-stretch">
                <button type="submit" class="btn btn-primary">
                    @if (isset($id))
                        Update
                    @else
                        Create
                    @endif
                </button>
                <a href={{ route('authors.index') }} class="ml-2 btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
