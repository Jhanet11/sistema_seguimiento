<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EDESSI') }}</title>

        <!-- Modo oscuro: se aplica antes de pintar la página -->
        <script>
            if (localStorage.getItem('modoOscuro') === 'true' ||
                (!localStorage.getItem('modoOscuro') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes flotar {
                0%, 100% { transform: translate(0, 0); }
                50% { transform: translate(20px, -20px); }
            }
            @keyframes aparecer {
                from { opacity: 0; transform: translateY(12px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes sacudir {
                0%, 100% { transform: translateX(0); }
                20%, 60% { transform: translateX(-8px); }
                40%, 80% { transform: translateX(8px); }
            }
            .blob-flotante { animation: flotar 10s ease-in-out infinite; }
            .blob-flotante-lenta { animation: flotar 14s ease-in-out infinite reverse; }
            .aparecer { animation: aparecer 0.5s ease-out; }
            .aparecer-tardio { animation: aparecer 0.5s ease-out 0.15s both; }
            .sacudir { animation: sacudir 0.4s ease-in-out; }
        </style>
    </head>
    <body class="font-sans antialiased">

        <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden
                    bg-gradient-to-br from-edessi-50 via-white to-acento-100
                    dark:from-noche-bg dark:via-noche-bg dark:to-noche-bg">

            <!-- Formas decorativas de fondo, difuminadas y con movimiento sutil -->
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full blur-3xl opacity-40 blob-flotante
                        bg-edessi-600 dark:bg-edessi-800 dark:opacity-30"></div>
            <div class="absolute -bottom-32 -right-24 w-[28rem] h-[28rem] rounded-full blur-3xl opacity-30 blob-flotante-lenta
                        bg-acento-500 dark:bg-acento-500 dark:opacity-25"></div>
            <div class="absolute top-1/3 right-0 w-72 h-72 rounded-full blur-3xl opacity-20 blob-flotante
                        bg-edessi-600 dark:bg-edessi-400 dark:opacity-20"></div>

            <!-- Botón de modo oscuro flotante -->
            <div class="absolute top-6 right-6 z-10"
                 x-data="{
                    oscuro: localStorage.getItem('modoOscuro') === 'true',
                    alternar() {
                        this.oscuro = !this.oscuro;
                        localStorage.setItem('modoOscuro', this.oscuro);
                        document.documentElement.classList.toggle('dark', this.oscuro);
                    }
                 }">
                <button @click="alternar()" class="p-2 rounded-full bg-white/70 dark:bg-noche-surface/70 backdrop-blur text-gray-600 dark:text-gray-300 shadow-sm hover:opacity-80">
                    <svg x-show="!oscuro" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="oscuro" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>

            <!-- Logo / marca -->
            <div class="relative z-10 mb-4 text-center aparecer">
                <img src="{{ asset('images/logo-edessi.jpg') }}" alt="EDESSI"
                     class="w-32 h-32 mx-auto rounded-2xl shadow-lg dark:hidden">
                <img src="{{ asset('images/logo-edessi-oscuro.jpg') }}" alt="EDESSI"
                     class="w-32 h-32 mx-auto rounded-2xl shadow-lg hidden dark:block">
            </div>

            <!-- Tarjeta del formulario -->
            <div class="relative z-10 w-full sm:max-w-md px-8 py-8 bg-white/90 dark:bg-noche-surface/90 backdrop-blur
                        shadow-xl rounded-2xl border border-white/50 dark:border-noche-border
                        aparecer-tardio {{ $errors->any() ? 'sacudir' : '' }}">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>