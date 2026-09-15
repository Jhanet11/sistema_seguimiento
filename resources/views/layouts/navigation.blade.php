<nav x-data="{ open: false }" class="bg-white dark:bg-noche-surface border-b border-gray-100 dark:border-noche-border">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo-edessi.jpg') }}" alt="EDESSI" class="w-9 h-9 rounded-lg dark:hidden">
                        <img src="{{ asset('images/logo-edessi-oscuro.jpg') }}" alt="EDESSI" class="w-9 h-9 rounded-lg hidden dark:block">
                        <span class="font-bold text-lg text-edessi-800 dark:text-transparent dark:bg-clip-text dark:bg-gradient-to-r dark:from-edessi-400 dark:to-acento-500">
                            EDESSI
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Panel') }}
                    </x-nav-link>

                    <x-nav-link :href="route('reparaciones.index')" :active="request()->routeIs('reparaciones.*')">
                        {{ __('Reparaciones') }}
                    </x-nav-link>

                    @unless (auth()->user()->esCliente())
                        <x-nav-link :href="route('equipos.index')" :active="request()->routeIs('equipos.*')">
                            {{ __('Equipos') }}
                        </x-nav-link>
                    @endunless

                    @if (auth()->user()->esAdmin())
                        <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                            {{ __('Clientes') }}
                        </x-nav-link>

                        <x-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')">
                            {{ __('Reportes') }}
                        </x-nav-link>

                        <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                            {{ __('Usuarios') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4"
                 x-data="{
                    oscuro: localStorage.getItem('modoOscuro') === 'true',
                    alternar() {
                        this.oscuro = !this.oscuro;
                        localStorage.setItem('modoOscuro', this.oscuro);
                        document.documentElement.classList.toggle('dark', this.oscuro);
                    }
                 }">

                {{-- Botón modo claro/oscuro --}}
                <button @click="alternar()" class="text-gray-500 hover:text-gray-700 dark:text-noche-border dark:hover:text-white">
                    <svg x-show="!oscuro" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="oscuro" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                {{-- Campanita de notificaciones --}}
                <div class="relative" x-data="{ abierta: false }">
                    <button @click="abierta = !abierta" @click.outside="abierta = false" class="relative text-gray-500 hover:text-gray-700 dark:text-noche-border dark:hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="abierta" x-cloak class="absolute right-0 mt-2 w-72 bg-white dark:bg-noche-surface dark:border-noche-border rounded-md shadow-lg border z-50 max-h-80 overflow-y-auto">
                        @forelse (auth()->user()->notifications->take(5) as $notif)
                            <div class="px-4 py-3 border-b dark:border-noche-border text-sm {{ $notif->read_at ? 'bg-white dark:bg-noche-surface' : 'bg-blue-50 dark:bg-noche-bg' }} dark:text-gray-200">
                                <p>{{ $notif->data['mensaje'] }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">Sin notificaciones.</p>
                        @endforelse
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-noche-border dark:hover:text-white focus:outline-none transition">
                            <div>{{ Auth::user()->nombre }}</div>
                            <span class="ml-1 text-xs text-gray-400 dark:text-gray-500">({{ ucfirst(Auth::user()->rol) }})</span>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-noche-bg focus:outline-none focus:bg-gray-100 dark:focus:bg-noche-bg focus:text-gray-500 dark:focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Panel') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('reparaciones.index')" :active="request()->routeIs('reparaciones.*')">
                {{ __('Reparaciones') }}
            </x-responsive-nav-link>

            @unless (auth()->user()->esCliente())
                <x-responsive-nav-link :href="route('equipos.index')" :active="request()->routeIs('equipos.*')">
                    {{ __('Equipos') }}
                </x-responsive-nav-link>
            @endunless

            @if (auth()->user()->esAdmin())
                <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                    {{ __('Clientes') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')">
                    {{ __('Reportes') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-noche-border">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-white">{{ Auth::user()->nombre }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }} — {{ ucfirst(Auth::user()->rol) }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>