<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Mis equipos</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @forelse ($equipos as $equipo)
                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6 border-l-4 border-edessi-600 dark:border-l-neon-cyan">
                    <h3 class="font-semibold mb-2 text-edessi-800 dark:text-white">{{ $equipo->tipo }} {{ $equipo->marca }} {{ $equipo->modelo }}</h3>

                    @forelse ($equipo->reparaciones as $r)
                        <div class="border-t dark:border-noche-border pt-3 mt-3">
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $r->falla_reportada }}</p>
                            @include('components.estado-progreso', ['estado' => $r->estado])
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Este equipo no tiene reparaciones registradas.</p>
                    @endforelse
                </div>
            @empty
                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6">
                    <p class="text-gray-500 dark:text-gray-400">Aún no tienes equipos registrados en el sistema.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>