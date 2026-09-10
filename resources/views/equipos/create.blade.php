<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar equipo</h2>
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

                <form action="{{ route('equipos.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select name="cliente_id" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="">-- Selecciona un cliente --</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            ¿El cliente no existe todavía? <a href="{{ route('clientes.create') }}" class="text-blue-600">Regístralo primero aquí</a>.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de equipo</label>
                        <input type="text" name="tipo" placeholder="Laptop, PC, Impresora..."
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Marca</label>
                        <input type="text" name="marca" class="mt-1 block w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Modelo</label>
                        <input type="text" name="modelo" class="mt-1 block w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Número de serie</label>
                        <input type="text" name="numero_serie" class="mt-1 block w-full rounded border-gray-300">
                    </div>

                    <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                        Registrar
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>