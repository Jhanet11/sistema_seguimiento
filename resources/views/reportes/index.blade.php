<x-app-layout>
<x-slot name="header">
<div>
<h1>Reportes de servicio</h1>
<p>Filtra las reparaciones por período, estado y técnico.</p>
</div>
<a class="btn btn-primary" href="{{ route('reportes.generar',request()->query()) }}">
<x-icon name="download"/>Descargar PDF</a>
</x-slot>
<section class="panel">
<form class="filters" method="GET">
<div class="field">
<label for="fecha_desde">Desde</label>
<input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}">
</div>
<div class="field">
<label for="fecha_hasta">Hasta</label>
<input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}">
</div>
<div class="field">
<label for="estado">Estado</label>
<select name="estado" id="estado">
<option value="">Todos</option>@foreach(\App\Models\Reparacion::ESTADOS as $key=>$label)<option value="{{ $key }}" @selected(request('estado')===$key)>{{ $label }}</option>@endforeach</select>
</div>@if(auth()->user()->esAdmin())<div class="field">
<label for="tecnico_id">Técnico</label>
<select name="tecnico_id" id="tecnico_id">
<option value="">Todos</option>@foreach($tecnicos as $t)<option value="{{ $t->id }}" @selected(request('tecnico_id')==$t->id)>{{ $t->nombre }}</option>@endforeach</select>
</div>@endif<button class="btn btn-primary">Aplicar filtros</button>
<a class="btn btn-secondary" href="{{ route('reportes.index') }}">Limpiar</a>
</form>
<div class="panel-head">
<h2>{{ $reparaciones->total() }} servicios encontrados</h2>
<span class="muted" style="font-size:11px">{{ auth()->user()->esTecnico()?'Órdenes asignadas a tu cuenta':'Todos los servicios del taller' }}</span>
</div>
<x-repair-table :reparaciones="$reparaciones"/>
<div class="table-foot">{{ $reparaciones->links() }}</div>
</section>
</x-app-layout>
