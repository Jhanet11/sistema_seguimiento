<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">{{ $cliente->nombre }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">C.I. {{ $cliente->ci }}</p>
            </div>
            <a href="{{ route('clientes.historial.pdf', $cliente) }}"
               class="px-4 py-2 bg-edessi-600 dark:bg-acento-500 text-white rounded hover:bg-edessi-700 dark:hover:opacity-90 text-sm">
                Descargar historial PDF
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Datos del cliente --}}
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg p-6 text-sm dark:text-gray-200">
                <p><strong>Teléfono:</strong> {{ $cliente->telefono ?? '-' }}</p>
                <p><strong>Dirección:</strong> {{ $cliente->direccion ?? '-' }}</p>
                <p><strong>Correo de contacto:</strong> {{ $cliente->correo_notificacion ?? '-' }}</p>
                <p><strong>Acceso al sistema:</strong> {{ $cliente->usuario ? 'Sí (login: '.$cliente->ci.')' : 'No' }}</p>
            </div>

            {{-- Historial tipo estado de cuenta, agrupado por mes --}}
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b dark:border-noche-border">
                    <h3 class="font-semibold dark:text-white">Historial de reparaciones</h3>
                </div>

                @forelse ($historialPorMes as $mes => $reparaciones)
                    <div>
                        {{-- Encabezado del mes --}}
                        <div class="bg-gray-50 dark:bg-noche-bg px-6 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase sticky top-0">
                            {{ ucfirst($mes) }}
                        </div>

                        {{-- Movimientos de ese mes --}}
                        @foreach ($reparaciones as $r)
                            <div x-data="{ abierto: false }" class="border-b dark:border-noche-border last:border-b-0">
                                <button @click="abierto = !abierto"
                                        class="w-full flex justify-between items-center px-6 py-3 text-left hover:bg-gray-50 dark:hover:bg-noche-bg transition">
                                    <div>
                                        <p class="text-sm font-medium dark:text-white">{{ $r->equipo->tipo }} {{ $r->equipo->marca }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $r->fecha_ingreso->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-1 rounded-full text-xs
                                            {{ $r->estado === 'entregado' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : 'bg-amber-100 text-amber-700 dark:bg-noche-bg dark:text-acento-500' }}">
                                            {{ ucfirst($r->estado) }}
                                        </span>
                                        <svg :class="abierto ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>

                                {{-- Detalle expandible --}}
                                <div x-show="abierto" class="px-6 pb-4 text-sm text-gray-700 dark:text-gray-300 space-y-2 bg-gray-50 dark:bg-noche-bg">
                                    <p><strong>Falla reportada:</strong> {{ $r->falla_reportada }}</p>
                                    <p><strong>Técnico:</strong> {{ $r->tecnico->nombre ?? 'Sin asignar' }}</p>
                                    <p><strong>Fecha de entrega:</strong> {{ $r->fecha_entrega ? $r->fecha_entrega->format('d/m/Y') : 'Aún no entregado' }}</p>

                                    @if ($r->observaciones->count())
                                        <div>
                                            <strong>Observaciones:</strong>
                                            <ul class="list-disc list-inside">
                                                @foreach ($r->observaciones as $obs)
                                                    <li>{{ $obs->descripcion }} <span class="text-xs text-gray-400 dark:text-gray-500">({{ $obs->created_at->format('d/m/Y') }})</span></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if ($r->repuestos->count())
                                        <div>
                                            <strong>Repuestos usados:</strong>
                                            <ul class="list-disc list-inside">
                                                @foreach ($r->repuestos as $rep)
                                                    <li>{{ $rep->nombre }} (cantidad: {{ $rep->pivot->cantidad }})</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <a href="{{ route('reparaciones.show', $r) }}" class="text-edessi-600 dark:text-edessi-400 text-xs hover:underline inline-block mt-1">
                                        Ver reparación completa →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm p-6">Este cliente aún no tiene reparaciones registradas.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>