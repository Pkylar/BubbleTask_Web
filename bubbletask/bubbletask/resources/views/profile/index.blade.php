@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<h2 class="text-2xl mb-6 font-semibold">User Profile</h2>

<div class="bg-white p-6 rounded shadow max-w-md">
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Joined At:</strong> {{ $user->created_at->format('d M Y') }}</p>

    <a href="{{ route('profile.edit') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Edit Profile
    </a>
</div>
@endsection
