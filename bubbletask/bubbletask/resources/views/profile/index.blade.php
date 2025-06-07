@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Success Messages -->
    @if (session('status') === 'profile-updated')
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            Profile updated successfully!
        </div>
    @elseif (session('status') === 'password-updated')
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            Password updated successfully!
        </div>
    @elseif (session('status') === 'image-updated')
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            Profile picture updated successfully!
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Profile</h1>
        </div>

        <!-- Profile Picture and Name Section -->
        <div class="text-center mb-8">
            <div class="relative inline-block">
                @if ($user->profile_picture)
                    <img src="{{ auth()->user()->profile_picture ?: asset('images/default-profile.png') }}" 
                     alt="Profile Picture" 
                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-300"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     onload="console.log('Avatar loaded successfully');">
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-300 mx-auto border-4 border-white shadow-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @endif
                <!-- Edit icon - Langsung ke edit image -->
                <a href="{{ route('profile.edit', ['type' => 'image']) }}" class="absolute bottom-0 right-0 bg-teal-500 rounded-full p-2 shadow-lg hover:bg-teal-600 transition-colors">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </a>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mt-4">{{ $user->name }}</h2>
            <p class="text-gray-600 mt-2">{{ $user->email }}</p>
        </div>

        <!-- Account Section -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Account</h3>
            
            <div class="space-y-4">
                <!-- Change Account Name -->
                <a href="{{ route('profile.edit', ['type' => 'name']) }}" class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-medium text-gray-800">Change account name</span>
                            <p class="text-sm text-gray-500">Current: {{ $user->name }}</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <!-- Change Account Password -->
                <a href="{{ route('profile.edit', ['type' => 'password']) }}" class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-medium text-gray-800">Change account password</span>
                            <p class="text-sm text-gray-500">Update your password for security</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <!-- Change Account Image -->
                <a href="{{ route('profile.edit', ['type' => 'image']) }}" class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-medium text-gray-800">Change account Image</span>
                            <p class="text-sm text-gray-500">Update your profile picture</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Additional Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 font-medium">← Back to Home</a>
                
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection