<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Modo oscuro: se aplica ANTES de pintar la página para evitar parpadeo -->
        <script>
            if (localStorage.getItem('modoOscuro') === 'true' ||
                (!localStorage.getItem('modoOscuro') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative min-h-screen overflow-x-hidden
                    bg-gradient-to-br from-edessi-50 via-gray-50 to-acento-100
                    dark:from-noche-bg dark:via-noche-bg dark:to-noche-bg">

            <!-- Formas decorativas de fondo, difuminadas y fijas -->
            <div class="fixed -top-40 -left-40 w-96 h-96 rounded-full blur-3xl opacity-30 pointer-events-none
                        bg-edessi-400 dark:bg-edessi-800 dark:opacity-25"></div>
            <div class="fixed top-1/2 -right-40 w-96 h-96 rounded-full blur-3xl opacity-20 pointer-events-none
                        bg-acento-500 dark:bg-edessi-600 dark:opacity-20"></div>

            <div class="relative z-10">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/80 dark:bg-noche-surface/80 backdrop-blur shadow-sm dark:border-b dark:border-noche-border">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>