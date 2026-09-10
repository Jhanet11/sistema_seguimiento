<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Mi panel</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3 text-edessi-800 dark:text-white">Mis reparaciones activas</h3>
                @forelse ($misReparaciones as $r)
                    <div class="border-b dark:border-noche-border py-2 text-sm flex justify-between items-center">
                        <span class="dark:text-gray-200">#{{ $r->id }} — {{ $r->equipo->tipo }} ({{ $r->equipo->cliente->nombre }})</span>
                        <span class="flex items-center gap-2">
                            <span class="px-2 py-1 rounded text-xs bg-edessi-50 text-edessi-700 dark:bg-noche-bg dark:text-neon-cyan">{{ ucfirst($r->estado) }}</span>
                            <a href="{{ route('reparaciones.show', $r) }}" class="text-edessi-600 dark:text-neon-cyan hover:underline">Ver</a>
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No tienes reparaciones asignadas activas.</p>
                @endforelse
            </div>

            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3 text-edessi-800 dark:text-white">Reparaciones sin asignar</h3>
                @forelse ($sinAsignar as $r)
                    <div class="border-b dark:border-noche-border py-2 text-sm flex justify-between items-center">
                        <span class="dark:text-gray-200">#{{ $r->id }} — {{ $r->equipo->tipo }} ({{ $r->equipo->cliente->nombre }})</span>
                        <form action="{{ route('reparaciones.autoasignar', $r) }}" method="POST">
                            @csrf
                            <button class="text-acento-600 dark:text-neon-pink hover:underline font-medium">Autoasignarme</button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No hay reparaciones libres en este momento.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>