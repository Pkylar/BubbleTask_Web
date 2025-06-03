@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<h2 class="text-2xl mb-6 font-semibold">Edit Profile</h2>

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="max-w-md space-y-4">
    @csrf
    @method('PATCH')

    <div>
        <label class="block font-semibold mb-1" for="name">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
               class="w-full border border-gray-300 rounded px-3 py-2" required>
        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1" for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
               class="w-full border border-gray-300 rounded px-3 py-2" required>
        @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1" for="profile_picture">Profile Picture</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="w-full">
        @error('profile_picture') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror

        @if ($user->profile_picture)
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="mt-2 w-24 h-24 rounded-full object-cover">
        @endif
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
</form>
@endsection
