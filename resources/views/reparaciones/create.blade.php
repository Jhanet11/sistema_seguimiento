<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Registrar ingreso de equipo</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reparaciones.store') }}" method="POST" class="space-y-4" x-data="{ enviando: false }" @submit="enviando = true">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Equipo</label>
                        <select name="equipo_id" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>
                            <option value="">-- Selecciona un equipo --</option>
                            @foreach ($equipos as $equipo)
                                <option value="{{ $equipo->id }}">
                                    {{ $equipo->tipo }} {{ $equipo->marca }} — {{ $equipo->cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            ¿El equipo no existe todavía?
                            <a href="{{ route('equipos.create') }}" class="text-edessi-600 dark:text-edessi-400 hover:text-edessi-800 dark:hover:text-acento-500 transition-colors">Regístralo primero aquí</a>.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Falla reportada</label>
                        <textarea name="falla_reportada" rows="3" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de ingreso</label>
                        <input type="date" name="fecha_ingreso" value="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>
                    </div>

                    <x-boton-enviar texto="Registrar" textoEnviando="Registrando..." />
                </form>

            </div>
        </div>
    </div>
</x-app-layout>