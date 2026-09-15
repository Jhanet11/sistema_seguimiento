<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-edessi-800 dark:text-white leading-tight">
                Reparación #{{ $reparacion->id }}
            </h2>
            @if (auth()->user()->esAdmin())
                <a href="{{ route('reparaciones.comprobante', $reparacion) }}" target="_blank"
                   class="px-4 py-2 bg-edessi-600 text-white rounded-lg text-sm
                          transition-all duration-150 hover:bg-edessi-700 dark:hover:bg-edessi-500 hover:shadow-md active:scale-95">
                    Ver comprobante (QR)
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 rounded">{{ session('error') }}</div>
            @endif

            {{-- Barra de progreso visual --}}
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">
                @include('components.estado-progreso', ['estado' => $reparacion->estado])
            </div>

            {{-- Datos generales --}}
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6 space-y-2 dark:text-gray-200">
                <p><strong>Equipo:</strong> {{ $reparacion->equipo->tipo }} {{ $reparacion->equipo->marca }} {{ $reparacion->equipo->modelo }}</p>
                <p><strong>Cliente:</strong> {{ $reparacion->equipo->cliente->nombre }}</p>
                <p><strong>Técnico asignado:</strong> {{ $reparacion->tecnico->nombre ?? 'Sin asignar' }}</p>
                <p><strong>Falla reportada:</strong> {{ $reparacion->falla_reportada }}</p>
                <p><strong>Fecha de ingreso:</strong> {{ $reparacion->fecha_ingreso->format('d/m/Y') }}</p>
                @if ($reparacion->fecha_entrega)
                    <p><strong>Fecha de entrega:</strong> {{ $reparacion->fecha_entrega->format('d/m/Y') }}</p>
                @endif
            </div>

            {{-- Asignar técnico (solo admin) --}}
            @if (auth()->user()->esAdmin())
                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">
                    <h3 class="font-semibold mb-3 text-edessi-800 dark:text-white">Asignar técnico</h3>
                    <form action="{{ route('reparaciones.asignar', $reparacion) }}" method="POST" class="flex gap-2">
                        @csrf
                        <select name="tecnico_id" class="rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>
                            <option value="">-- Selecciona técnico --</option>
                            @foreach (\App\Models\Usuario::where('rol', 'tecnico')->get() as $tecnico)
                                <option value="{{ $tecnico->id }}" @selected($reparacion->tecnico_id === $tecnico->id)>
                                    {{ $tecnico->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <button class="px-4 py-2 bg-edessi-600 text-white rounded-lg
                                       transition-all duration-150 hover:bg-edessi-700 dark:hover:bg-edessi-500 hover:shadow-md active:scale-95">
                            Asignar
                        </button>
                    </form>
                </div>
            @endif

            {{-- Cambiar estado (admin o técnico asignado) --}}
            @if (auth()->user()->esAdmin() || auth()->id() === $reparacion->tecnico_id)
                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">
                    <h3 class="font-semibold mb-3 text-edessi-800 dark:text-white">Actualizar estado</h3>
                    <form action="{{ route('reparaciones.estado', $reparacion) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="estado" class="rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>
                            @foreach (['recibido', 'diagnostico', 'reparacion', 'listo', 'entregado'] as $estado)
                                <option value="{{ $estado }}" @selected($reparacion->estado === $estado)>
                                    {{ ucfirst($estado) }}
                                </option>
                            @endforeach
                        </select>
                        <button class="px-4 py-2 bg-acento-500 text-white rounded-lg
                                       transition-all duration-150 hover:bg-acento-600 hover:shadow-md active:scale-95">
                            Actualizar
                        </button>
                    </form>
                </div>

                {{-- Agregar observación --}}
                <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">
                    <h3 class="font-semibold mb-3 text-edessi-800 dark:text-white">Agregar observación técnica</h3>
                    <form action="{{ route('observaciones.store', $reparacion) }}" method="POST" class="space-y-2">
                        @csrf
                        <textarea name="descripcion" rows="2" class="w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white"
                                  placeholder="Ej: se cambió memoria RAM..." required></textarea>
                        <button class="px-4 py-2 bg-edessi-600 text-white rounded-lg
                                       transition-all duration-150 hover:bg-edessi-700 dark:hover:bg-edessi-500 hover:shadow-md active:scale-95">
                            Guardar observación
                        </button>
                    </form>
                </div>
            @endif

            {{-- Historial de observaciones --}}
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">
                <h3 class="font-semibold mb-3 text-edessi-800 dark:text-white">Observaciones</h3>
                @forelse ($reparacion->observaciones as $obs)
                    <div class="border-b dark:border-noche-border py-2 text-sm">
                        <p class="dark:text-gray-200">{{ $obs->descripcion }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $obs->tecnico->nombre }} — {{ $obs->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Sin observaciones registradas.</p>
                @endforelse
            </div>

            {{-- Repuestos usados --}}
            <div class="bg-white dark:bg-noche-surface dark:border dark:border-noche-border shadow-sm rounded-xl p-6">
                <h3 class="font-semibold mb-3 text-edessi-800 dark:text-white">Repuestos utilizados</h3>
                @forelse ($reparacion->repuestos as $rep)
                    <p class="text-sm dark:text-gray-200">{{ $rep->nombre }} — cantidad: {{ $rep->pivot->cantidad }}</p>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Sin repuestos registrados.</p>
                @endforelse

                @if (auth()->user()->esAdmin() || auth()->id() === $reparacion->tecnico_id)
                    <form action="{{ route('reparaciones.repuestos.store', $reparacion) }}" method="POST"
                          class="mt-4 pt-4 border-t dark:border-noche-border space-y-2">
                        @csrf

                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Repuesto del catálogo</label>
                        <select name="repuesto_id" class="block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white">
                            <option value="">-- Selecciona --</option>
                            @foreach ($catalogoRepuestos as $rep)
                                <option value="{{ $rep->id }}">{{ $rep->nombre }}</option>
                            @endforeach
                        </select>

                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            ¿No está en la lista? Escribe uno nuevo
                        </label>
                        <input type="text" name="repuesto_nuevo" placeholder="Ej: Ventilador CPU"
                               class="block w-full rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white dark:placeholder-gray-500">

                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                        <input type="number" name="cantidad" value="1" min="1"
                               class="block w-32 rounded border-gray-300 dark:bg-noche-bg dark:border-noche-border dark:text-white" required>

                        <button class="px-4 py-2 bg-edessi-600 text-white rounded-lg
                                       transition-all duration-150 hover:bg-edessi-700 dark:hover:bg-edessi-500 hover:shadow-md active:scale-95">
                            Registrar repuesto
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>