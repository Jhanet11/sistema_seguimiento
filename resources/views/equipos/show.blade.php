<x-app-layout>
<x-slot name="header">
<div>
<span class="eyebrow muted">FICHA DEL EQUIPO</span>
<h1 style="margin-top:8px">{{ $equipo->tipo }} {{ $equipo->marca }}</h1>
<p>{{ $equipo->modelo ?: 'Modelo no registrado' }} · {{ $equipo->cliente->nombre }}</p>
</div>
<div style="display:flex;gap:8px;flex-wrap:wrap">@if(auth()->user()->esAdmin())<a class="btn btn-secondary" href="{{ route('equipos.edit',$equipo) }}">Editar equipo</a>@endif @if(!auth()->user()->esCliente())<a class="btn btn-primary" href="{{ route('reparaciones.create',['equipo_id'=>$equipo->id]) }}">
<x-icon name="plus"/>Nueva reparación</a>@endif</div>
</x-slot>
<div class="details-grid">
<section class="panel">
<div class="panel-head">
<h2>Historial de reparaciones</h2>
</div>
<x-repair-table :reparaciones="$equipo->reparaciones"/>
</section>
<aside class="stack">
<section class="panel">
<div class="panel-head">
<h2>Datos de recepción</h2>
</div>
<div class="panel-body">
<dl class="data-list">
<div>
<dt>Cliente</dt>
<dd>{{ $equipo->cliente->nombre }}</dd>
</div>
<div>
<dt>Número de serie</dt>
<dd>{{ $equipo->numero_serie ?: 'No registrado' }}</dd>
</div>
<div>
<dt>Accesorios</dt>
<dd>{{ $equipo->accesorios ?: 'No registrados' }}</dd>
</div>
<div>
<dt>Estado físico</dt>
<dd>{{ $equipo->estado_visual ?: 'No registrado' }}</dd>
</div>
</dl>
</div>
</section>@if(auth()->user()->esAdmin() && $equipo->reparaciones->isEmpty())<form method="POST" action="{{ route('equipos.destroy',$equipo) }}" onsubmit="return confirm('¿Eliminar este equipo sin historial?')">@csrf @method('DELETE')<button class="btn btn-danger">Eliminar equipo sin historial</button>
</form>@endif</aside>
</div>
</x-app-layout>
