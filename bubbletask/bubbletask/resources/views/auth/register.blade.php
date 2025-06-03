@extends('layouts.auth')

@section('content')
<div class="flex flex-col justify-center w-full max-w-md mx-auto">
    <h2 class="text-3xl font-bold mb-8 text-[#3D3F91] text-center">Create your BubbleTask account</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-6 w-full">
        @csrf

        {{-- Username --}}
        <div>
            <label for="name" class="block text-[#3D3F91] font-semibold mb-2">Username</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3D3F91]" />
            @error('name')
                <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-[#3D3F91] font-semibold mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3D3F91]" />
            @error('email')
                <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-[#3D3F91] font-semibold mb-2">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3D3F91]" />
            @error('password')
                <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-[#3D3F91] font-semibold mb-2">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3D3F91]" />
        </div>

        {{-- Submit Button --}}
        <button type="submit"
            class="w-full bg-[#3D3F91] text-white font-semibold py-3 rounded hover:bg-[#2b2d65] transition-colors">
            Register
        </button>
    </form>

    <p class="mt-6 text-center text-[#3D3F91]">
        Already have an account? 
        <a href="{{ route('login') }}" class="underline hover:text-[#2b2d65] font-semibold">Login here</a>
    </p>
</div>
@endsection
