<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar ingreso de equipo</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reparaciones.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Equipo</label>
                        <select name="equipo_id" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="">-- Selecciona un equipo --</option>
                            @foreach ($equipos as $equipo)
                                <option value="{{ $equipo->id }}">
                                    {{ $equipo->tipo }} {{ $equipo->marca }} — {{ $equipo->cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            ¿El equipo no existe todavía? <a href="{{ route('equipos.create') }}" class="text-blue-600">Regístralo primero aquí</a>.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Falla reportada</label>
                        <textarea name="falla_reportada" rows="3" class="mt-1 block w-full rounded border-gray-300" required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de ingreso</label>
                        <input type="date" name="fecha_ingreso" value="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                        Registrar
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>