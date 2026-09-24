<x-app-layout>
<x-slot name="header">
<div>
<h1>Clientes</h1>
<p>Contacto, equipos e historial de cada cliente.</p>
</div>
<a class="btn btn-primary" href="{{ route('clientes.create') }}">
<x-icon name="plus"/>Registrar cliente</a>
</x-slot>
<section class="panel">
<form class="filters">
<div class="field" style="flex:1">
<label for="q">Buscar cliente</label>
<input id="q" name="q" value="{{ request('q') }}" placeholder="Nombre, C.I. o teléfono">
</div>
<button class="btn btn-primary">
<x-icon name="search"/>Buscar</button>
<a class="btn btn-secondary" href="{{ route('clientes.index') }}">Limpiar</a>
</form>
<div class="table-scroll">
<table>
<thead>
<tr>
<th>Cliente</th>
<th>Contacto</th>
<th>Equipos</th>
<th>Acceso web</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>@forelse($clientes as $c)<tr>
<td>
<strong>{{ $c->nombre }}</strong>
<small>C.I. {{ $c->ci }}</small>
</td>
<td>{{ $c->telefono ?: 'Sin teléfono' }}<small>{{ $c->correo_notificacion }}</small>
</td>
<td>{{ $c->equipos_count }}</td>
<td>
<span class="badge {{ $c->usuario_id?'badge-listo':'badge-entregado' }}">{{ $c->usuario_id?'Habilitado':'Sin cuenta' }}</span>
</td>
<td>
<a class="link" href="{{ route('clientes.show',$c) }}">Ver historial →</a>
</td>
</tr>@empty<tr>
<td colspan="5">
<div class="empty">
<x-icon name="users"/>
<strong>No se encontraron clientes</strong>Registra un cliente para comenzar una atención.</div>
</td>
</tr>@endforelse</tbody>
</table>
</div>
<div class="table-foot">{{ $clientes->links() }}</div>
</section>
</x-app-layout>
