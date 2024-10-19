@extends('layouts.app')

@section('content')
<div class="container">
    <div class="my-4">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to users</a>
    </div>
    <h1>User Details</h1>
    <p><strong>User Name:</strong> {{ $user->name }}</p>
    <p><strong>User Email:</strong> {{ $user->email }}</p>
    <p><strong>Created Date:</strong> {{ $user->created_at }}</p>

</div>
@endsection
