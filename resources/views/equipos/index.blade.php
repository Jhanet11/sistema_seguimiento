<x-app-layout>
<x-slot name="header">
<div>
<h1>{{ auth()->user()->esCliente()?'Mis equipos':'Equipos' }}</h1>
<p>Dispositivos registrados y su historial de servicio.</p>
</div>@if(!auth()->user()->esCliente())<a class="btn btn-primary" href="{{ route('equipos.create') }}">
<x-icon name="plus"/>Registrar equipo</a>@endif</x-slot>
<section class="panel">
<form class="filters">
<div class="field" style="flex:1">
<label for="q">Buscar equipo</label>
<input id="q" name="q" value="{{ request('q') }}" placeholder="Tipo, marca, número de serie o cliente">
</div>
<button class="btn btn-primary">
<x-icon name="search"/>Buscar</button>
<a class="btn btn-secondary" href="{{ route('equipos.index') }}">Limpiar</a>
</form>
<div class="table-scroll">
<table>
<thead>
<tr>
<th>Equipo</th>
<th>Número de serie</th>
<th>Cliente</th>
<th>Servicios</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>@forelse($equipos as $e)<tr>
<td>
<strong>{{ $e->tipo }} {{ $e->marca }}</strong>
<small>{{ $e->modelo }}</small>
</td>
<td>{{ $e->numero_serie ?: 'No registrado' }}</td>
<td>{{ $e->cliente->nombre }}</td>
<td>{{ $e->reparaciones_count }}</td>
<td>
<a class="link" href="{{ route('equipos.show',$e) }}">Ver equipo →</a>
</td>
</tr>@empty<tr>
<td colspan="5">
<div class="empty">
<x-icon name="monitor"/>
<strong>No hay equipos para mostrar</strong>Los dispositivos registrados aparecerán aquí.</div>
</td>
</tr>@endforelse</tbody>
</table>
</div>
<div class="table-foot">{{ $equipos->links() }}</div>
</section>
</x-app-layout>
