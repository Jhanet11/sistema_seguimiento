<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Panel de Administración</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Tarjetas resumen: degradados estilo CRM en modo oscuro --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gradient-to-br dark:from-neon-purple dark:to-neon-pink shadow-sm rounded-lg p-4 border-t-4 border-edessi-600 dark:border-none">
                    <p class="text-sm text-gray-500 dark:text-white/80">Total reparaciones</p>
                    <p class="text-2xl font-bold text-edessi-700 dark:text-white">{{ $totalReparaciones }}</p>
                </div>
                <div class="bg-white dark:bg-gradient-to-br dark:from-neon-cyan dark:to-edessi-600 shadow-sm rounded-lg p-4 border-t-4 border-acento-500 dark:border-none">
                    <p class="text-sm text-gray-500 dark:text-white/80">Sin técnico asignado</p>
                    <p class="text-2xl font-bold text-acento-600 dark:text-white">{{ $sinAsignar }}</p>
                </div>
                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-4 border-t-4 border-edessi-600">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Clientes</p>
                    <p class="text-2xl font-bold text-edessi-700 dark:text-neon-cyan">{{ $totalClientes }}</p>
                </div>
                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-4 border-t-4 border-edessi-600">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Equipos</p>
                    <p class="text-2xl font-bold text-edessi-700 dark:text-neon-cyan">{{ $totalEquipos }}</p>
                </div>
            </div>

            {{-- Por estado: gráfico de barras --}}
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4 text-edessi-800 dark:text-white">Reparaciones por estado</h3>
                @php
                    $coloresBarra = [
                        'recibido' => 'bg-gray-400 dark:bg-gray-500',
                        'diagnostico' => 'bg-edessi-600 dark:bg-neon-cyan',
                        'reparacion' => 'bg-acento-500 dark:bg-neon-pink',
                        'listo' => 'bg-green-500',
                        'entregado' => 'bg-teal-500 dark:bg-neon-purple',
                    ];
                    $maximo = max(1, collect($porEstado)->max() ?? 1);
                @endphp
                <div class="space-y-3">
                    @foreach (['recibido', 'diagnostico', 'reparacion', 'listo', 'entregado'] as $estado)
                        @php $cantidad = $porEstado[$estado] ?? 0; @endphp
                        <div class="flex items-center gap-3">
                            <span class="w-24 text-sm text-gray-600 dark:text-gray-300 shrink-0">{{ ucfirst($estado) }}</span>
                            <div class="flex-1 bg-gray-100 dark:bg-noche-bg rounded-full h-5 overflow-hidden">
                                <div class="h-full {{ $coloresBarra[$estado] }} rounded-full transition-all duration-500"
                                     style="width: {{ $maximo > 0 ? ($cantidad / $maximo) * 100 : 0 }}%"></div>
                            </div>
                            <span class="w-6 text-sm font-semibold text-edessi-800 dark:text-white text-right">{{ $cantidad }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Últimas reparaciones --}}
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-edessi-800 dark:text-white">Últimas reparaciones registradas</h3>
                    <a href="{{ route('reparaciones.index') }}" class="text-edessi-600 dark:text-neon-cyan text-sm hover:underline">Ver todas</a>
                </div>
                @forelse ($ultimasReparaciones as $r)
                    <div class="border-b dark:border-noche-border py-2 text-sm flex justify-between">
                        <span class="dark:text-gray-200">#{{ $r->id }} — {{ $r->equipo->tipo }} ({{ $r->equipo->cliente->nombre }})</span>
                        <span class="text-gray-500 dark:text-gray-400">{{ ucfirst($r->estado) }} — {{ $r->tecnico->nombre ?? 'Sin asignar' }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aún no hay reparaciones.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>