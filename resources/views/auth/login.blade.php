<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Bienvenido de nuevo</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ingresa tus datos para continuar</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ enviando: false }" @submit="enviando = true">
        @csrf

        <!-- Email o C.I. -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo o C.I.</label>
            <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white
                          transition-shadow duration-200
                          focus:border-edessi-600 focus:ring-2 focus:ring-edessi-600/40 dark:focus:border-edessi-400 dark:focus:ring-edessi-400/40
                          shadow-sm py-2.5 px-4">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password con mostrar/ocultar -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>

            <div class="relative mt-1">
                <input :type="show ? 'text' : 'password'"
                       id="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       class="block w-full rounded-xl border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white
                              transition-shadow duration-200
                              focus:border-edessi-600 focus:ring-2 focus:ring-edessi-600/40 dark:focus:border-edessi-400 dark:focus:ring-edessi-400/40
                              shadow-sm py-2.5 px-4 pr-11">

                <button type="button"
                        @click="show = !show"
                        class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        tabindex="-1">
                    <svg x-show="!show" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                    <svg x-show="show" x-cloak class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-gray-300 dark:border-noche-border text-edessi-600 dark:text-acento-500 shadow-sm focus:ring-edessi-600 dark:focus:ring-acento-500">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-edessi-600 dark:text-edessi-400 hover:underline" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit"
                :disabled="enviando"
                class="w-full py-2.5 rounded-xl text-white font-medium shadow-sm transition-all duration-150
                       bg-gradient-to-r from-edessi-600 to-edessi-700 hover:opacity-90 hover:shadow-md active:scale-[0.98]
                       dark:from-edessi-800 dark:to-acento-500
                       disabled:opacity-70 disabled:cursor-not-allowed
                       flex items-center justify-center gap-2">
            <svg x-show="enviando" x-cloak class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span x-text="enviando ? 'Ingresando...' : 'Iniciar sesión'"></span>
        </button>
    </form>
</x-guest-layout>