<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Reportes de reparaciones</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6">

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Filtra los datos que quieres incluir en el reporte. Puedes dejar campos vacíos para no filtrar por ellos.
                </p>

                <form action="{{ route('reportes.generar') }}" method="GET" class="space-y-4" target="_blank">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Desde</label>
                            <input type="date" name="fecha_desde" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta</label>
                            <input type="date" name="fecha_hasta" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Técnico</label>
                        <select name="tecnico_id" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white">
                            <option value="">-- Todos --</option>
                            @foreach ($tecnicos as $tecnico)
                                <option value="{{ $tecnico->id }}">{{ $tecnico->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                        <select name="estado" class="mt-1 block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white">
                            <option value="">-- Todos --</option>
                            @foreach (['recibido', 'diagnostico', 'reparacion', 'listo', 'entregado'] as $estado)
                                <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="px-4 py-2 bg-edessi-600 dark:bg-acento-500 text-white rounded hover:bg-edessi-700 dark:hover:opacity-90">
                        Generar PDF
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>