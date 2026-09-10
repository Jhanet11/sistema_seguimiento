<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Registrar cliente</h2>
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

                <form action="{{ route('clientes.store') }}" method="POST" class="space-y-4" x-data="{ crearAcceso: false }">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre completo</label>
                        <input type="text" name="nombre" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">C.I.</label>
                        <input type="text" name="ci" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                        <input type="text" name="telefono" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección</label>
                        <input type="text" name="direccion" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo de contacto (opcional)</label>
                        <input type="email" name="correo_notificacion" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white"
                               placeholder="Para avisarle cuando su equipo esté listo">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            No es obligatorio. Si no lo da, igual podrá ver el estado de su equipo ingresando al sistema con su C.I.
                        </p>
                    </div>

                    <div class="border-t dark:border-noche-border pt-4">
                        <label class="flex items-center gap-2 dark:text-gray-200">
                            <input type="checkbox" name="crear_acceso" value="1">
                            <span class="text-sm">Crear acceso al sistema para que consulte sus reparaciones</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            El cliente ingresará al sistema con su C.I. como usuario. La contraseña inicial será <code>password</code>, y deberá cambiarla luego.
                        </p>
                    </div>

                    <button class="px-4 py-2 bg-edessi-600 dark:bg-neon-pink text-white rounded hover:bg-edessi-700 dark:hover:opacity-90">
                        Registrar
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>