@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-md mx-auto">
    @php
        $editType = request()->get('type', 'name');
        $titles = [
            'name' => 'Edit Profile',
            'password' => 'Change Password', 
            'image' => 'Change Profile Picture'
        ];
    @endphp

    <h2 class="text-2xl mb-6 font-semibold">{{ $titles[$editType] ?? 'Edit Profile' }}</h2>

    @if($editType === 'name')
        <!-- Edit Name Form -->
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <input type="hidden" name="edit_type" value="name">

            <div>
                <label class="block font-semibold mb-1" for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
        </form>

    @elseif($editType === 'password')
        <!-- Change Password Form -->
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <input type="hidden" name="edit_type" value="password">

            <div>
                <label class="block font-semibold mb-1" for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password"
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
                @error('current_password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1" for="password">New Password</label>
                <input type="password" id="password" name="password"
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
                @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1" for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
                @error('password_confirmation') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
        </form>

    @elseif($editType === 'image')
        <!-- Change Profile Picture Form -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')
            <input type="hidden" name="edit_type" value="image">

            <div>
                <label class="block font-semibold mb-1" for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" 
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
                @error('profile_picture') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror

                @if ($user->profile_picture)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Current Profile Picture:</p>
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                             alt="Current Profile Picture" 
                             class="w-24 h-24 rounded-full object-cover">
                    </div>
                @endif
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
        </form>

    @else
        <!-- Default/Full Edit Form -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
    @endif

    <!-- Back to Profile Link -->
    <div class="mt-4">
        <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-800 text-sm">← Back to Profile</a>
    </div>
</div>
@endsection