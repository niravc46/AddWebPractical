@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>All users</h1>
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
            <form action="{{ route('users.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <div class="pr-2">
                        <input type="text" name="search" class="form-control" placeholder="Search users..."
                            value="{{ old('search', $search) }}">
                    </div>
                    <div class="input-group-append ">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">View</a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <p class="text-muted">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }}
                    users</p>
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@endsection
