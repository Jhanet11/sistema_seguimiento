<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">
            Reparaciones
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6" x-data="{ buscar: '' }">

                <div class="flex justify-between items-center mb-4 gap-4">
                    @if (auth()->user()->esAdmin())
                        <a href="{{ route('reparaciones.create') }}"
                           class="px-4 py-2 bg-edessi-600 dark:bg-acento-500 text-white rounded hover:bg-edessi-700 dark:hover:opacity-90 whitespace-nowrap transition-all duration-150 hover:shadow-md active:scale-95">
                            + Registrar ingreso de equipo
                        </a>
                    @endif

                    <input type="text" x-model="buscar" placeholder="Buscar por cliente, equipo o técnico..."
                           class="rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white dark:placeholder-gray-500 text-sm w-full max-w-xs">
                </div>

                <table class="min-w-full text-sm text-left">
                    <thead class="border-b dark:border-noche-border">
                        <tr>
                            <th class="py-2 pr-4 dark:text-gray-300">#</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Equipo</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Cliente</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Técnico</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Estado</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Ingreso</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reparaciones as $r)
                            <tr class="border-b dark:border-noche-border"
                                x-show="$el.innerText.toLowerCase().includes(buscar.toLowerCase())">
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $r->id }}</td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $r->equipo->tipo }} {{ $r->equipo->marca }}</td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $r->equipo->cliente->nombre ?? '-' }}</td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $r->tecnico->nombre ?? 'Sin asignar' }}</td>
                                <td class="py-2 pr-4">
                                    <span class="px-2 py-1 rounded text-xs bg-gray-100 dark:bg-noche-bg dark:text-edessi-400">{{ ucfirst($r->estado) }}</span>
                                </td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $r->fecha_ingreso->format('d/m/Y') }}</td>
                                <td class="py-2 pr-4 space-x-2">
                                    <a href="{{ route('reparaciones.show', $r) }}" class="text-edessi-600 dark:text-edessi-400 hover:underline">Ver</a>

                                    @if (auth()->user()->esTecnico() && $r->estaSinAsignar())
                                        <form action="{{ route('reparaciones.autoasignar', $r) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="text-green-600 dark:text-green-400 hover:underline">Autoasignarme</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-4 text-gray-500 dark:text-gray-400">No hay reparaciones registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>