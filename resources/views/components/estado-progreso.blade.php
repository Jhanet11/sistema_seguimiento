@php
    $estados = ['recibido' => 'Recibido', 'diagnostico' => 'Diagnóstico', 'reparacion' => 'Reparación', 'listo' => 'Listo', 'entregado' => 'Entregado'];
    $claves = array_keys($estados);
    $indiceActual = array_search($estado, $claves);
@endphp

<div class="flex items-center">
    @foreach ($estados as $clave => $etiqueta)
        @php $indice = $loop->index; @endphp

        @if (!$loop->first)
            <div class="flex-1 h-0.5 mb-5 {{ $indice <= $indiceActual ? 'bg-edessi-600 dark:bg-neon-cyan' : 'bg-gray-200 dark:bg-noche-border' }}"></div>
        @endif

        <div class="flex flex-col items-center flex-1">
            @if ($indice < $indiceActual)
                {{-- Completado --}}
                <div class="w-7 h-7 rounded-full bg-edessi-600 dark:bg-neon-cyan flex items-center justify-center text-white dark:text-noche-bg text-xs">✓</div>
            @elseif ($indice === $indiceActual)
                {{-- Estado actual --}}
                <div class="w-7 h-7 rounded-full bg-acento-500 dark:bg-neon-pink flex items-center justify-center text-white text-xs font-semibold">{{ $indice + 1 }}</div>
            @else
                {{-- Pendiente --}}
                <div class="w-7 h-7 rounded-full bg-white dark:bg-noche-surface border border-gray-300 dark:border-noche-border flex items-center justify-center text-gray-400 dark:text-gray-500 text-xs">{{ $indice + 1 }}</div>
            @endif

            <span class="text-xs mt-1 text-center {{ $indice === $indiceActual ? 'text-acento-600 dark:text-neon-pink font-medium' : ($indice < $indiceActual ? 'text-edessi-700 dark:text-neon-cyan' : 'text-gray-400 dark:text-gray-500') }}">
                {{ $etiqueta }}
            </span>
        </div>
    @endforeach
</div>