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

        <div class="display: inline-block;">
            <div>
                <h1>Create New Post</h1>
                <div id="loading-spinner" class="spinner-border text-primary" style="display:none;" role="status">
                </div>
            </div>

        </div>
        <form id="post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-2">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group mb-2">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" rows="10"></textarea>
            </div>
            <div class="form-group mb-2">
                <label for="images">Upload Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
            </div>
            <div id="image-preview" class="row mt-3 mb-2"></div>

            <div class="form-group mt-4">
                <button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
                <a href="{{ route('posts.index') }}" class="btn btn-danger">cancel</a>
            </div>
        </form>
    </div>

@section('scripts')
    <script>
        setTimeout(function() {
            $('.alert-danger').alert('close');
        }, 3000);

        // Preview the selected images
        document.getElementById('images').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('image-preview');
            previewContainer.innerHTML = '';
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

        // Loader on submit form
        document.getElementById('post-form').addEventListener('submit', function(event) {
            const submitButton = document.getElementById('submit-button');
            const loadingSpinner = document.getElementById('loading-spinner');
            submitButton.disabled = true;
            loadingSpinner.style.display = 'inline-block';
        });
    </script>
@endsection

@endsection
