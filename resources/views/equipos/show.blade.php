<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $equipo->tipo }} {{ $equipo->marca }} {{ $equipo->modelo }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm rounded-lg p-6">
                <p><strong>Cliente:</strong> {{ $equipo->cliente->nombre }}</p>
                <p><strong>N° Serie:</strong> {{ $equipo->numero_serie ?? '-' }}</p>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Historial de reparaciones</h3>
                @forelse ($equipo->reparaciones as $r)
                    <div class="border-b py-2 text-sm">
                        <p><strong>#{{ $r->id }}</strong> — {{ ucfirst($r->estado) }} — {{ $r->fecha_ingreso->format('d/m/Y') }}</p>
                        <p class="text-gray-600">{{ $r->falla_reportada }}</p>
                        <a href="{{ route('reparaciones.show', $r) }}" class="text-blue-600 text-xs hover:underline">Ver detalle</a>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Sin reparaciones registradas.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>