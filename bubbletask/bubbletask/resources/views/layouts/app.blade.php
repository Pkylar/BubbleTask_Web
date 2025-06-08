<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BubbleTask - @yield('title')</title>

    {{-- Import CSS dan JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js CDN (jika belum dimasukkan di app.js) --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Tambahan khusus per halaman --}}
    @yield('head')
</head>
<body class="bg-gray-100 font-sans min-h-screen">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('partials.sidebar')

        <div class="flex-1 flex flex-col">
            {{-- Header --}}
            @include('partials.header')

            {{-- Main Content --}}
            <main class="p-6 flex-1">
                @yield('content')
            </main>
        </div>
    </div>
@stack('scripts')
</body>
</html>
