<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Usuarios del sistema</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6" x-data="{ buscar: '' }">

                <div class="flex justify-between items-center mb-4 gap-4">
                    <a href="{{ route('usuarios.create') }}"
                       class="px-4 py-2 bg-edessi-600 dark:bg-acento-500 text-white rounded hover:bg-edessi-700 dark:hover:opacity-90 whitespace-nowrap transition-all duration-150 hover:shadow-md active:scale-95">
                        + Crear usuario
                    </a>

                    <input type="text" x-model="buscar" placeholder="Buscar por nombre o correo..."
                           class="rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white dark:placeholder-gray-500 text-sm w-full max-w-xs">
                </div>

                <table class="min-w-full text-sm text-left">
                    <thead class="border-b dark:border-noche-border">
                        <tr>
                            <th class="py-2 pr-4 dark:text-gray-300">Nombre</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Correo</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Rol</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Estado</th>
                            <th class="py-2 pr-4 dark:text-gray-300">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr class="border-b dark:border-noche-border" x-show="$el.innerText.toLowerCase().includes(buscar.toLowerCase())">
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $usuario->nombre }}</td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ $usuario->email }}</td>
                                <td class="py-2 pr-4 dark:text-gray-200">{{ ucfirst($usuario->rol) }}</td>
                                <td class="py-2 pr-4">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $usuario->activo ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-500 dark:bg-noche-bg dark:text-gray-400' }}">
                                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="py-2 pr-4">
                                    @if ($usuario->id !== auth()->id())
                                        <form action="{{ route('usuarios.toggle', $usuario) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-sm {{ $usuario->activo ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} hover:underline">
                                                {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">(Tú)</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-gray-500 dark:text-gray-400">No hay usuarios registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>