@extends('layouts.auth')

@section('content')
<div class="flex flex-col justify-center w-full max-w-md mx-auto">
    <h2 class="text-3xl font-bold mb-8 text-[#3D3F91] text-center">Welcome back to BubbleTask!</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-6 w-full">
        @csrf

        {{-- Email or Username --}}
        <div>
            <label for="email" class="block text-[#3D3F91] font-semibold mb-2">Email or Username</label>
            <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus
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

        {{-- Remember Me --}}
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="mr-2" />
            <label for="remember_me" class="text-[#3D3F91] font-medium">Remember me</label>
        </div>

        {{-- Submit Button --}}
        <button type="submit"
            class="w-full bg-[#3D3F91] text-white font-semibold py-3 rounded hover:bg-[#2b2d65] transition-colors">
            Login
        </button>
    </form>

    <p class="mt-6 text-center text-[#3D3F91]">
        Don’t have an account? 
        <a href="{{ route('register') }}" class="underline hover:text-[#2b2d65] font-semibold">Register here</a>
    </p>
</div>
@endsection
