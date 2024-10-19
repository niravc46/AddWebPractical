@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h1>All Posts</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between mb-3">
            <form action="{{ route('posts.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <!-- Search Input -->
                    <div class="pr-2">
                        <input type="text" name="search" class="form-control" placeholder="Search posts..."
                            value="{{ old('search', $search) }}">
                    </div>

                    {{-- Author filter dropdown --}}
                    <div class="pr-2 mx-2">
                        <select name="author" class="form-control">
                            <option value="">Select Author</option>
                            @foreach($authors as $authorOption)
                                <option value="{{ $authorOption->id }}" {{ $authorOption->id == old('author', $author) ? 'selected' : '' }}>
                                    {{ $authorOption->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date range  --}}
                    <div class="pr-2 mx-2">
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
                    </div>
                    <div class="pr-2">
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
                    </div>

                    {{-- search button  --}}
                    <div class="input-group-append mx-2">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </form>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
        </div>

        <!-- Posts Table -->
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $index => $post)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->author->name }}</td>
                        <td>{{ $post->created_at }}</td>
                        <td>
                            @if (Auth::user()->id !== $post->user_id && Auth::user()->hasRole('Admin'))
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                    style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm">View</a>
                            @elseif (Auth::user()->id === $post->user_id)
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                    style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm">View</a>
                            @else
                                <button class="btn btn-warning btn-sm" disabled>Edit</button>
                                <button class="btn btn-danger btn-sm" disabled>Delete</button>
                                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm">View</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <p class="text-muted">Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }} of {{ $posts->total() }} posts</p>
            </div>
            <div>
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        setTimeout(function() {
            $('.alert-success').alert('close');
        }, 3000);

        $(document).ready(function() {
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                // jQuery confirm dialog
                if (confirm('Are you sure you want to delete this post?')) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
@endsection
