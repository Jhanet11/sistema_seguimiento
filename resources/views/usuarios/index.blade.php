<x-app-layout>
<x-slot name="header">
<div>
<h1>Usuarios</h1>
<p>Gestiona los roles y el acceso de tu equipo de trabajo.</p>
</div>
<a class="btn btn-primary" href="{{ route('usuarios.create') }}">
<x-icon name="plus"/>Crear usuario</a>
</x-slot>
<section class="panel">
<div class="table-scroll">
<table>
<thead>
<tr>
<th>Usuario</th>
<th>Rol</th>
<th>Estado</th>
<th>Servicios</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>@forelse($usuarios as $u)<tr>
<td>
<strong>{{ $u->nombre }}</strong>
<small>{{ $u->email }}</small>
</td>
<td>{{ $u->esAdmin()?'Administrador':'Técnico' }}</td>
<td>
<span class="badge {{ $u->activo?'badge-listo':'badge-entregado' }}">{{ $u->activo?'Activo':'Inactivo' }}</span>
</td>
<td>{{ $u->reparaciones_count }}</td>
<td>
<div style="display:flex;gap:8px;align-items:center">
<a class="btn btn-secondary btn-sm" href="{{ route('usuarios.edit',$u) }}">Editar</a>@if($u->id!==auth()->id())<form method="POST" action="{{ route('usuarios.toggle',$u) }}">@csrf @method('PATCH')<button class="btn btn-secondary btn-sm">{{ $u->activo?'Desactivar':'Activar' }}</button>
</form>
<form method="POST" action="{{ route('usuarios.destroy',$u) }}" onsubmit="return confirm('¿Eliminar esta cuenta sin actividad?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Eliminar</button>
</form>@endif</div>
</td>
</tr>@empty<tr>
<td colspan="5" class="empty">No hay usuarios registrados.</td>
</tr>@endforelse</tbody>
</table>
</div>
<div class="table-foot">{{ $usuarios->links() }}</div>
</section>
</x-app-layout>
