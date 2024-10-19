@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="my-4">
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to Posts</a>
        </div>
        <h1>{{ $post->title }}</h1>
        <p><strong>Author:</strong> {{ $post->author->name }}</p>
        <p>{{ $post->content }}</p>

        <h3>Images:</h3>
        <div class="row">
            @foreach ($post->images as $image)
                <div class="col-md-4 mb-3">
                    <img src="{{ $image->image_path }}" alt="Post Image" class="img-fluid">
                </div>
            @endforeach
        </div>


    </div>
@section('scripts')
    <script>
        setTimeout(function() {
            $('.alert-success').alert('close');
        }, 3000);
    </script>
@endsection
@endsection
