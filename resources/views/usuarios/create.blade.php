<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Crear usuario</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre completo</label>
                        <input type="text" name="nombre" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo electrónico</label>
                        <input type="email" name="email" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
                        <select name="rol" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>
                            <option value="tecnico">Técnico</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                        <input type="password" name="password" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required minlength="6">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Mínimo 6 caracteres. El usuario podrá cambiarla luego desde su perfil.</p>
                    </div>

                    <button class="px-4 py-2 bg-edessi-600 dark:bg-neon-pink text-white rounded hover:bg-edessi-700 dark:hover:opacity-90">
                        Crear usuario
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>