<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">
            {{ $equipo->tipo }} {{ $equipo->marca }} {{ $equipo->modelo }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6 dark:text-gray-200">
                <p><strong>Cliente:</strong> {{ $equipo->cliente->nombre }}</p>
                <p><strong>N° Serie:</strong> {{ $equipo->numero_serie ?? '-' }}</p>
            </div>

            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">
                <h3 class="font-semibold mb-3 text-edessi-800 dark:text-white">Historial de reparaciones</h3>
                @forelse ($equipo->reparaciones as $r)
                    <div class="border-b dark:border-noche-border py-2 text-sm">
                        <p class="dark:text-gray-200"><strong>#{{ $r->id }}</strong> — {{ ucfirst($r->estado) }} — {{ $r->fecha_ingreso->format('d/m/Y') }}</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ $r->falla_reportada }}</p>
                        <a href="{{ route('reparaciones.show', $r) }}"
                           class="text-edessi-600 dark:text-edessi-400 text-xs hover:text-edessi-800 dark:hover:text-acento-500 hover:underline transition-colors">
                            Ver detalle
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Sin reparaciones registradas.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>