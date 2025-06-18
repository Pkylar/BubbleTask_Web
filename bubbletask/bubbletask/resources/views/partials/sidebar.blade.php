@php
    $currentRoute = Route::currentRouteName();
@endphp

<nav class="w-64 p-6 flex flex-col space-y-4" style="background-color: #C2E1FC;">
    {{-- Logo di atas --}}
    <div class="mb-6 flex justify-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-32 h-auto" />
    </div>

    {{-- Form Pencarian Tugas --}}
    <div>
        <form action="{{ route('home') }}" method="GET" class="flex items-center space-x-2 px-2 py-1 bg-white border rounded-md">
            <input 
                type="text" 
                name="search" 
                placeholder="Search tasks..."
                value="{{ request('search') }}"
                class="w-full border-none px-2 py-1"
            />
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm">Search</button>
        </form>
    </div>

    {{-- Tombol Add Task dengan warna latar belakang dan border yang sama --}}
    <a href="{{ route('tasks.create') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md bg-orange-400 text-white border-2 border-orange-400">
        <!-- ikon plus -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <span class="font-semibold">Add Task</span>
    </a>

    <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md
        {{ request()->routeIs('home') ? 'bg-blue-800 text-white' : 'text-gray-700 hover:bg-blue-400' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" 
            viewBox="0 0 24 24" fill="currentColor">
            <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
            <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z" />
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
