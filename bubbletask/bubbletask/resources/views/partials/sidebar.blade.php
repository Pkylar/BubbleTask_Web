@php
    $currentRoute = Route::currentRouteName();
@endphp

<nav class="w-64 p-6 flex flex-col space-y-4" style="background-color: #C2E1FC;">
    {{-- Logo di atas --}}
    <div class="mb-6 flex justify-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-32 h-auto" />
    </div>

    {{-- Tombol Add Task warna oranye di atas --}}
    <a href="{{ route('tasks.create') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md
        {{ request()->routeIs('tasks.create') ? 'bg-orange-400 text-white' : 'text-orange-600 hover:bg-orange-300' }}">
        <!-- ikon plus -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <span class="font-semibold">Add Task</span>
    </a>

    <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md
        {{ request()->routeIs('home') ? 'bg-blue-800 text-white' : 'text-gray-700 hover:bg-blue-400' }}">
        <!-- ikon home -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
        </svg>
        <span class="font-semibold">Home</span>
    </a>

    <a href="{{ route('calendar') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md
        {{ request()->routeIs('calendar') ? 'bg-blue-800 text-white' : 'text-gray-700 hover:bg-blue-400' }}">
        <!-- ikon kalender -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
        </svg>
        <span class="font-semibold">Calendar</span>
    </a>

    <a href="{{ route('priority') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md
        {{ request()->routeIs('priority') ? 'bg-blue-800 text-white' : 'text-gray-700 hover:bg-blue-400' }}">
        <!-- ikon priority -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 8v4l3 3" />
            <circle cx="12" cy="12" r="10" />
        </svg>
        <span class="font-semibold">Priority</span>
    </a>

    <a href="{{ route('profile') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md
        {{ request()->routeIs('profile') ? 'bg-blue-800 text-white' : 'text-gray-700 hover:bg-blue-400' }}">
        <!-- ikon profile -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span class="font-semibold">Profile</span>
    </a>
</nav>
