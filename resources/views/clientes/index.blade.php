<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clientes</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6" x-data="{ buscar: '' }">

                <div class="flex justify-between items-center mb-4 gap-4">
                    <a href="{{ route('clientes.create') }}"
                       class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 whitespace-nowrap">
                        + Registrar cliente
                    </a>

                    <input type="text" x-model="buscar" placeholder="Buscar por nombre o C.I..."
                           class="rounded border-gray-300 text-sm w-full max-w-xs">
                </div>

                <table class="min-w-full text-sm text-left">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2 pr-4">#</th>
                            <th class="py-2 pr-4">Nombre</th>
                            <th class="py-2 pr-4">C.I.</th>
                            <th class="py-2 pr-4">Teléfono</th>
                            <th class="py-2 pr-4">Tiene acceso</th>
                            <th class="py-2 pr-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientes as $cliente)
                            <tr class="border-b"
                                x-show="$el.innerText.toLowerCase().includes(buscar.toLowerCase())">
                                <td class="py-2 pr-4">{{ $cliente->id }}</td>
                                <td class="py-2 pr-4">{{ $cliente->nombre }}</td>
                                <td class="py-2 pr-4">{{ $cliente->ci }}</td>
                                <td class="py-2 pr-4">{{ $cliente->telefono ?? '-' }}</td>
                                <td class="py-2 pr-4">{{ $cliente->usuario ? 'Sí' : 'No' }}</td>
                                <td class="py-2 pr-4">
                                    <a href="{{ route('clientes.show', $cliente) }}" class="text-blue-600 hover:underline">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-4 text-gray-500">No hay clientes registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>