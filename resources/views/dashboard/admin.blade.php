<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">Panel de Administración</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Banner de bienvenida con degradado de marca --}}
            <div class="rounded-xl p-6 text-white shadow-sm bg-gradient-to-r from-edessi-700 via-edessi-600 to-edessi-500
                        dark:from-noche-surface dark:via-edessi-900 dark:to-edessi-700 dark:border dark:border-noche-border">
                <p class="text-sm text-white/80">Bienvenido de nuevo</p>
                <p class="text-xl font-semibold">{{ auth()->user()->nombre }}</p>
                <p class="text-sm text-white/70 mt-1">{{ now()->translatedFormat('l d \d\e F, Y') }}</p>
            </div>

            {{-- Tarjetas resumen con íconos de colores --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-4 flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-edessi-100 dark:bg-edessi-800/40 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-edessi-600 dark:text-edessi-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M5 7h14M5 7a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2M5 7h14" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total reparaciones</p>
                        <p class="text-xl font-bold text-edessi-700 dark:text-white">{{ $totalReparaciones }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-4 flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-acento-100 dark:bg-acento-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-acento-600 dark:text-acento-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Sin técnico asignado</p>
                        <p class="text-xl font-bold text-acento-600 dark:text-acento-500">{{ $sinAsignar }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-4 flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-green-100 dark:bg-green-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Clientes</p>
                        <p class="text-xl font-bold text-green-700 dark:text-green-400">{{ $totalClientes }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-4 flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-edessi-100 dark:bg-edessi-800/40 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-edessi-600 dark:text-edessi-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7a2 2 0 012-2h6a2 2 0 012 2v10m-10 0H5a2 2 0 01-2-2v-2a2 2 0 012-2h.01M9 17H5m4-10H5a2 2 0 00-2 2v2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Equipos</p>
                        <p class="text-xl font-bold text-edessi-700 dark:text-edessi-400">{{ $totalEquipos }}</p>
                    </div>
                </div>
            </div>

            {{-- Por estado: gráfico de barras --}}
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">
                <h3 class="font-semibold mb-4 text-edessi-800 dark:text-white">Reparaciones por estado</h3>
                @php
                    $coloresBarra = [
                        'recibido' => 'bg-gray-400 dark:bg-gray-500',
                        'diagnostico' => 'bg-edessi-500 dark:bg-edessi-400',
                        'reparacion' => 'bg-acento-500',
                        'listo' => 'bg-green-500',
                        'entregado' => 'bg-edessi-800 dark:bg-edessi-700',
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
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-edessi-800 dark:text-white">Últimas reparaciones registradas</h3>
                    <a href="{{ route('reparaciones.index') }}" class="text-edessi-600 dark:text-edessi-400 text-sm hover:underline">Ver todas</a>
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