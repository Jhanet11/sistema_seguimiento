<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Clientes</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6" x-data="{ buscar: '' }">

                <div class="flex justify-between items-center mb-4 gap-4">
                    <a href="{{ route('clientes.create') }}"
                       class="px-4 py-2 bg-edessi-600 dark:bg-acento-500 text-white rounded hover:bg-edessi-700 dark:hover:opacity-90 whitespace-nowrap transition-all duration-150 hover:shadow-md active:scale-95">
                        + Registrar cliente
                    </a>

                    <input type="text" x-model="buscar" placeholder="Buscar por nombre o C.I..."
                           class="rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white dark:placeholder-gray-500 text-sm w-full max-w-xs">
                </div>

                <table class="min-w-full text-sm text-left">
                    <thead class="border-b dark:border-noche-border">
                        <tr>
                            <th class="py-2 pr-4 dark:text-gray-300">#</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Nombre</th>
                            <th class="py-2 pr-4 dark:text-gray-300">C.I.</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Teléfono</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Tiene acceso</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientes as $cliente)
                            <tr class="border-b dark:border-noche-border"
                                x-show="$el.innerText.toLowerCase().includes(buscar.toLowerCase())">
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $cliente->id }}</td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $cliente->nombre }}</td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $cliente->ci }}</td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $cliente->telefono ?? '-' }}</td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $cliente->usuario ? 'Sí' : 'No' }}</td>
                                <td class="py-2 pr-4">
                                    <a href="{{ route('clientes.show', $cliente) }}" class="text-edessi-600 dark:text-edessi-400 hover:underline">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-4 text-gray-500 dark:text-gray-400">No hay clientes registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>