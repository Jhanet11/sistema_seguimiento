<x-app-layout>
<x-slot name="header">
<div>
<span class="eyebrow muted">ORDEN DE SERVICIO</span>
<h1 style="margin-top:8px">Reparación #{{ str_pad($reparacion->id,4,'0',STR_PAD_LEFT) }}</h1>
<p>{{ $reparacion->equipo->tipo }} {{ $reparacion->equipo->marca }} {{ $reparacion->equipo->modelo }} · {{ $reparacion->equipo->cliente->nombre }}</p>
</div>
<div style="display:flex;gap:9px;flex-wrap:wrap">
<a class="btn btn-secondary" href="{{ route('reparaciones.index') }}">Volver</a>@if(!auth()->user()->esCliente())<a class="btn btn-primary" href="{{ route('reparaciones.comprobante',$reparacion) }}">
<x-icon name="download"/>Comprobante PDF</a>@endif</div>
</x-slot>
<div class="panel" style="margin-bottom:24px">@include('components.estado-progreso',['estado'=>$reparacion->estado])</div>
<div class="details-grid">
<div class="stack">
<section class="panel">
<div class="panel-head">
<h2>Información del servicio</h2>
<x-status :estado="$reparacion->estado"/>
</div>
<div class="panel-body">
<dl class="data-list">
<div>
<dt>Falla reportada</dt>
<dd style="white-space:pre-wrap">{{ $reparacion->falla_reportada }}</dd>
</div>
<div>
<dt>Diagnóstico técnico</dt>
<dd style="white-space:pre-wrap">{{ $reparacion->diagnostico ?: 'Pendiente de diagnóstico' }}</dd>
</div>
</dl>
<div class="form-grid" style="margin-top:23px">
<dl class="data-list">
<div>
<dt>Fecha de ingreso</dt>
<dd>{{ $reparacion->fecha_ingreso->format('d/m/Y') }}</dd>
</div>
<div>
<dt>Entrega estimada</dt>
<dd>{{ $reparacion->fecha_estimada?->format('d/m/Y')??'Por definir' }}</dd>
</div>
</dl>
<dl class="data-list">
<div>
<dt>Costo del servicio</dt>
<dd>{{ $reparacion->costo!==null?'Bs '.number_format($reparacion->costo,2):'Pendiente de definir' }}</dd>
</div>
<div>
<dt>Entrega al cliente</dt>
<dd>{{ $reparacion->fecha_entrega?->format('d/m/Y')??'Aún no entregado' }}</dd>
</div>
</dl>
</div>
</div>
</section>
@can('update',$reparacion) @if($reparacion->estado!=='entregado')<section class="panel">
<div class="panel-head">
<h2>Actualizar datos del servicio</h2>
</div>
<form class="panel-body" method="POST" action="{{ route('reparaciones.update',$reparacion) }}">@csrf @method('PATCH')<div class="form-grid">
<div class="field span-2">
<label for="falla_reportada">Falla reportada *</label>
<textarea name="falla_reportada" id="falla_reportada" rows="2" maxlength="5000" required>{{ old('falla_reportada',$reparacion->falla_reportada) }}</textarea>
</div>
<div class="field span-2">
<label for="diagnostico">Diagnóstico</label>
<textarea name="diagnostico" id="diagnostico" rows="3" maxlength="5000">{{ old('diagnostico',$reparacion->diagnostico) }}</textarea>
</div>
<div class="field">
<label for="costo">Costo del servicio (Bs)</label>
<input type="number" name="costo" id="costo" min="0" max="99999999.99" step="0.01" value="{{ old('costo',$reparacion->costo) }}">
<small>Importe ingresado por el técnico.</small>
</div>
<div class="field">
<label for="fecha_estimada">Entrega estimada</label>
<input type="date" name="fecha_estimada" id="fecha_estimada" value="{{ old('fecha_estimada',$reparacion->fecha_estimada?->toDateString()) }}" min="{{ $reparacion->fecha_ingreso->toDateString() }}">
</div>
</div>
<div class="form-actions">
<button class="btn btn-primary">Guardar detalles</button>
</div>
</form>
</section>@endif @endcan
<section class="panel">
<div class="panel-head">
<h2>Observaciones técnicas</h2>
<span class="muted">{{ $reparacion->observaciones->count() }}</span>
</div>
<div class="panel-body">@forelse($reparacion->observaciones->sortByDesc('id') as $obs)<div class="timeline-item">
<p style="white-space:pre-wrap">{{ $obs->descripcion }}</p>
<small>{{ $obs->tecnico?->nombre??'Personal del taller' }} · {{ $obs->created_at->format('d/m/Y H:i') }}</small>
</div>@empty<p class="muted" style="font-size:12px">Aún no se registraron observaciones.</p>@endforelse @can('update',$reparacion) @if($reparacion->estado!=='entregado')<form method="POST" action="{{ route('observaciones.store',$reparacion) }}" style="margin-top:20px">@csrf<div class="field">
<label for="descripcion">Nueva observación</label>
<textarea id="descripcion" name="descripcion" rows="3" required maxlength="5000" placeholder="Describe el trabajo realizado">{{ old('descripcion') }}</textarea>
</div>
<button class="btn btn-secondary" style="margin-top:12px">Agregar observación</button>
</form>@endif @endcan</div>
</section>
<section class="panel">
<div class="panel-head">
<h2>Repuestos utilizados</h2>
</div>
<div class="panel-body">@forelse($reparacion->repuestos as $rep)<div style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:13px">
<span>{{ $rep->nombre }}</span>
<strong>× {{ $rep->pivot->cantidad }}</strong>
</div>@empty<p class="muted" style="font-size:12px">Sin repuestos registrados.</p>@endforelse @can('update',$reparacion) @if($reparacion->estado!=='entregado')<form method="POST" action="{{ route('reparaciones.repuestos.store',$reparacion) }}" style="margin-top:22px">@csrf<div class="form-grid">
<div class="field">
<label for="repuesto_id">Repuesto del catálogo</label>
<select name="repuesto_id" id="repuesto_id">
<option value="">Selecciona un repuesto</option>@foreach($catalogoRepuestos as $rep)<option value="{{ $rep->id }}" @selected(old('repuesto_id')==$rep->id)>{{ $rep->nombre }}</option>@endforeach</select>
</div>
<div class="field">
<label for="repuesto_nuevo">O registra uno nuevo</label>
<input name="repuesto_nuevo" id="repuesto_nuevo" maxlength="255" value="{{ old('repuesto_nuevo') }}" placeholder="Nombre del repuesto">
</div>
<div class="field">
<label for="cantidad">Cantidad *</label>
<input type="number" name="cantidad" id="cantidad" min="1" max="10000" value="{{ old('cantidad',1) }}" required>
</div>
</div>
<button class="btn btn-secondary" style="margin-top:18px">Registrar repuesto</button>
</form>@endif @endcan</div>
</section>
</div>
<aside class="stack">
<section class="panel">
<div class="panel-head">
<h2>Responsable y estado</h2>
</div>
<div class="panel-body">
<p class="muted" style="font-size:11px">Técnico asignado</p>
<strong style="display:block;margin:6px 0 22px">{{ $reparacion->tecnico?->nombre??'Por asignar' }}</strong>@if(auth()->user()->esAdmin() && $reparacion->estado!=='entregado')<form method="POST" action="{{ route('reparaciones.asignar',$reparacion) }}" class="field" style="margin-bottom:23px">@csrf<label for="tecnico_id">Asignar o cambiar técnico</label>
<select name="tecnico_id" id="tecnico_id" required>
<option value="">Selecciona un técnico activo</option>@foreach($tecnicos as $t)<option value="{{ $t->id }}" @selected($reparacion->tecnico_id===$t->id)>{{ $t->nombre }}</option>@endforeach</select>
<button class="btn btn-secondary">Guardar asignación</button>
</form>@endif @if(auth()->user()->esTecnico() && !$reparacion->tecnico_id && $reparacion->estado!=='entregado')<form method="POST" action="{{ route('reparaciones.autoasignar',$reparacion) }}">@csrf<button class="btn btn-primary">Asignarme esta orden</button>
</form>@endif
@can('update',$reparacion) @if($reparacion->estado!=='entregado')<form method="POST" action="{{ route('reparaciones.estado',$reparacion) }}" class="field">@csrf @method('PATCH')<label for="estado">Etapa del servicio</label>
<select name="estado" id="estado">@foreach(\App\Models\Reparacion::ESTADOS as $key=>$label)<option value="{{ $key }}" @selected($reparacion->estado===$key)>{{ $label }}</option>@endforeach</select>
<button class="btn btn-primary">Actualizar estado</button>
</form>@else<p class="notice" style="margin:0">Servicio finalizado y entregado.</p>@endif @endcan</div>
</section>
<section class="panel">
<div class="panel-head">
<h2>Equipo recibido</h2>
<x-icon name="monitor" class="muted"/>
</div>
<div class="panel-body">
<dl class="data-list">
<div>
<dt>Tipo y modelo</dt>
<dd>{{ $reparacion->equipo->tipo }} {{ $reparacion->equipo->marca }} {{ $reparacion->equipo->modelo }}</dd>
</div>
<div>
<dt>Número de serie</dt>
<dd>{{ $reparacion->equipo->numero_serie?:'No registrado' }}</dd>
</div>
<div>
<dt>Accesorios</dt>
<dd>{{ $reparacion->equipo->accesorios?:'No registrados' }}</dd>
</div>
<div>
<dt>Estado físico</dt>
<dd>{{ $reparacion->equipo->estado_visual?:'No registrado' }}</dd>
</div>
</dl>
<a class="link" style="display:block;margin-top:20px" href="{{ route('equipos.show',$reparacion->equipo) }}">Ver ficha del equipo →</a>
</div>
</section>
<section class="panel">
<div class="panel-head">
<h2>Historial de estados</h2>
</div>
<div class="panel-body">@forelse($reparacion->historial as $h)<div class="timeline-item">
<strong>{{ \App\Models\Reparacion::ESTADOS[$h->estado] }}</strong>
<small>{{ $h->usuario?->nombre??'Sistema' }}<br>{{ $h->created_at->format('d/m/Y H:i') }}</small>
</div>@empty<p class="muted" style="font-size:12px">Todavía no hay cambios de estado registrados.</p>@endforelse</div>
</section>
@if(auth()->user()->esAdmin() && $reparacion->estado==='recibido' && $reparacion->observaciones->isEmpty() && $reparacion->repuestos->isEmpty() && $reparacion->historial->count()<=1)<form method="POST" action="{{ route('reparaciones.destroy',$reparacion) }}" onsubmit="return confirm('¿Eliminar esta orden sin actividad técnica?')">@csrf @method('DELETE')<button class="btn btn-danger">Eliminar orden sin actividad</button>
</form>@endif</aside>
</div>
</x-app-layout>
