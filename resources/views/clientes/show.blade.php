<x-app-layout>
<x-slot name="header">
<div>
<span class="eyebrow muted">FICHA DEL CLIENTE</span>
<h1 style="margin-top:8px">{{ $cliente->nombre }}</h1>
<p>C.I. {{ $cliente->ci }} · {{ $cliente->telefono ?: 'Sin teléfono registrado' }}</p>
</div>
<div style="display:flex;gap:8px;flex-wrap:wrap">
<a class="btn btn-secondary" href="{{ route('clientes.historial.pdf',$cliente) }}">
<x-icon name="download"/>Historial PDF</a>@if(auth()->user()->esAdmin())<a class="btn btn-primary" href="{{ route('clientes.edit',$cliente) }}">Editar cliente</a>@endif</div>
</x-slot>
<div class="details-grid">
<div class="stack">
<section class="panel">
<div class="panel-head">
<h2>Equipos del cliente</h2>
<a class="link" href="{{ route('equipos.create',['cliente_id'=>$cliente->id]) }}">+ Registrar equipo</a>
</div>
<div class="panel-body">@forelse($cliente->equipos as $e)<a class="quick-link" href="{{ route('equipos.show',$e) }}">
<x-icon name="monitor"/>
<span>{{ $e->tipo }} {{ $e->marca }} {{ $e->modelo }}<small>Serie: {{ $e->numero_serie ?: 'No registrada' }}</small>
</span>
<span class="arrow">→</span>
</a>@empty<div class="empty">El cliente todavía no tiene equipos.</div>@endforelse</div>
</section>
<section class="panel">
<div class="panel-head">
<h2>Historial de servicios</h2>
</div>
<div class="panel-body">@forelse($historialPorMes as $mes=>$servicios)<h3 style="margin-bottom:20px;text-transform:capitalize">{{ $mes }}</h3>@foreach($servicios as $r)<div class="timeline-item">
<strong>Orden #{{ $r->id }} · {{ $r->equipo->tipo }} {{ $r->equipo->marca }}</strong>
<p style="margin:8px 0">{{ $r->falla_reportada }}</p>
<x-status :estado="$r->estado"/>@can('view',$r)<a class="link" style="margin-left:10px" href="{{ route('reparaciones.show',$r) }}">Ver detalle →</a>@endcan<small>{{ $r->fecha_ingreso->format('d/m/Y') }} · {{ $r->tecnico?->nombre??'Sin asignar' }}</small>
</div>@endforeach @empty<div class="empty">Aún no hay servicios registrados.</div>@endforelse</div>
</section>
</div>
<aside class="stack">
<section class="panel">
<div class="panel-head">
<h2>Información de contacto</h2>
</div>
<div class="panel-body">
<dl class="data-list">
<div>
<dt>Teléfono</dt>
<dd>{{ $cliente->telefono ?: 'No registrado' }}</dd>
</div>
<div>
<dt>Correo de notificaciones</dt>
<dd>{{ $cliente->correo_notificacion ?: 'No registrado' }}</dd>
</div>
<div>
<dt>Dirección</dt>
<dd>{{ $cliente->direccion ?: 'No registrada' }}</dd>
</div>
<div>
<dt>Acceso al portal</dt>
<dd>{{ $cliente->usuario_id?'Cuenta habilitada con C.I.':'Sin cuenta' }}</dd>
</div>
</dl>
</div>
</section>@if(auth()->user()->esAdmin() && $cliente->equipos->isEmpty())<form method="POST" action="{{ route('clientes.destroy',$cliente) }}" onsubmit="return confirm('¿Eliminar este cliente sin equipos y su cuenta de acceso?')">@csrf @method('DELETE')<button class="btn btn-danger">Eliminar cliente sin equipos</button>
</form>@endif</aside>
</div>
</x-app-layout>
