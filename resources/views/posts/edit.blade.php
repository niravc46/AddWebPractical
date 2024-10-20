@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <h1>Edit Post</h1>
        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Post Title -->
            <div class="form-group mb-2">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}">
            </div>

            <!-- Post Content -->
            <div class="form-group mb-2">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" rows="10">{{ $post->content }}</textarea>
            </div>

            <!-- Existing Images Preview -->
            <div class="form-group mb-2">
                <label>Previous Images</label>
                <div id="existing-images" class="row mt-3">
                    @foreach ($post->images as $image)
                        <div class="col-3 mb-2">
                            <img src="{{ asset('' . $image->image_path) }}" class="img-thumbnail me-2">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Upload New Images -->
            <div class="form-group mb-2">
                <label for="images">Upload New Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple>
            </div>

            <!-- New Images Preview -->
            <div id="image-preview" class="row mt-3 "></div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('posts.index') }}" class="btn btn-danger">cancel</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        setTimeout(function() {
            $('.alert-danger').alert('close');
        }, 3000);


        document.getElementById('images').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('image-preview');
            previewContainer.innerHTML = ''; // Clear existing preview
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail', 'col-3', 'me-2', 'mb-2');
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
