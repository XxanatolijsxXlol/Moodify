<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
       <title>@yield('title', config('app.name', 'Moodify'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinycolor/1.4.2/tinycolor.min.js"></script>

        <link rel="icon" href="{{ asset('storage/assets/favicon.png') }}" type="image/x-icon">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if (isset($activeTheme))
            <link href="{{ asset($activeTheme->css_path) }}" rel="stylesheet">
        @endif
      
        <style>
            /* Base padding for the top navigation bar */
            body {
                padding-top: 3.5rem; /* h-14 for mobile top navbar */
            }

            /* Adjustments for medium and larger screens (md breakpoint) */
            @media (min-width: 768px) {
                body {
                    padding-top: 4rem; /* h-16 for desktop top navbar */
                }
            }

            /* Padding for the mobile bottom navigation bar (h-16) */
            @media (max-width: 767px) {
                body {
                    padding-bottom: 4rem; /* h-16 for mobile bottom nav */
                }
            }

       
        </style>
    </head>
    <body id="background" class="font-sans antialiased bg-white text-white dark:bg-gray-900 dark:text-gray-100">
        <div class=" flex flex-col ">
            @include('layouts.navigation')

            @isset($header)
                <header class="md:ml-16 md:group-hover:ml-64 transition-all duration-300 ease-in-out">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h1>{{ $header }}</h1>
                    </div>
                </header>
            @endisset

            <main class="flex-1">
                
                <div id="content"
                     class="max-w-7xl mx-auto py-6 sm:px-0 lg:px-3
                            md:ml-16 md:group-hover:ml-64
                            transition-all duration-300 ease-in-out">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>