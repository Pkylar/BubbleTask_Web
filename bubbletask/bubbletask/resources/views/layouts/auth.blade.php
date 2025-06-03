<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - BubbleTask</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #F9E2F2; /* baby pink */
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen h-screen">

    <main class="flex flex-col md:flex-row items-center justify-center w-full h-full max-w-7xl p-6 rounded-lg shadow-lg mx-4 bg-[#C2E1FC]"> {{-- baby blue kotak --}}
        {{-- Bagian logo --}}
        <div class="w-full md:w-1/2 flex justify-center mb-8 md:mb-0">
            <img src="{{ asset('images/logo.png') }}" alt="BubbleTask Logo" class="max-w-xs md:max-w-md object-contain" />
        </div>

        {{-- Bagian konten form --}}
        <section class="w-full md:w-1/2 px-4 md:px-12">
            @yield('content')
        </section>
    </main>

</body>
</html>
