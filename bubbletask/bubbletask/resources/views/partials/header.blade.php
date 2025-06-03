<header class="px-6 py-4 flex justify-between items-center border-b border-gray-200" style="background-color: #F9E2F2;">
    <div class="flex items-center space-x-4">
        @auth
            {{-- Foto Profil --}}
            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('default-profile.png') }}" 
                 alt="Profile Picture" class="w-20 h-20 rounded-full object-cover">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Good Morning, {{ auth()->user()->name ?? 'User' }}!</h1>
                <p class="text-gray-600 text-sm">{{ auth()->user()->email ?? '' }}</p>
            </div>
        @else
            {{-- Jika belum login --}}
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Welcome, Guest!</h1>
                <p class="text-gray-600 text-sm">Please login to access your tasks</p>
            </div>
        @endauth
    </div>

    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:underline font-semibold">Logout</button>
        </form>
    @endauth
</header>
