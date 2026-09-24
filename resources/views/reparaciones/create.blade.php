<x-app-layout>
<x-slot name="header">
<div>
<h1>Nueva reparación</h1>
<p>Abre una orden de servicio para un equipo registrado.</p>
</div>
<a class="btn btn-secondary" href="{{ route('reparaciones.index') }}">Volver</a>
</x-slot>
<div class="form-shell">
<section class="panel">
<div class="panel-head">
<h2>Datos de recepción</h2>
<span class="badge badge-recibido">Nueva orden</span>
</div>
<form method="POST" action="{{ route('reparaciones.store') }}" class="panel-body">@csrf<div class="form-grid">
<div class="field span-2">
<label for="equipo_id">Equipo *</label>
<select name="equipo_id" id="equipo_id" required>
<option value="">Selecciona un equipo</option>@foreach($equipos as $e)<option value="{{ $e->id }}" @selected(old('equipo_id',request('equipo_id'))==$e->id)>{{ $e->tipo }} {{ $e->marca }} {{ $e->modelo }} · {{ $e->cliente->nombre }} · {{ $e->numero_serie ?: 'Sin serie' }}</option>@endforeach</select>
<small>Se muestran los equipos que no tienen una orden activa.</small>
<a class="link" href="{{ route('equipos.create') }}">+ Registrar un equipo nuevo</a>
</div>
<div class="field">
<label for="fecha_ingreso">Fecha de ingreso *</label>
<input type="date" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso',today()->toDateString()) }}" max="{{ today()->toDateString() }}" required>
</div>
<div class="field">
<label for="fecha_estimada">Entrega estimada</label>
<input type="date" id="fecha_estimada" name="fecha_estimada" value="{{ old('fecha_estimada') }}">
</div>@if(auth()->user()->esAdmin())<div class="field span-2">
<label for="tecnico_id">Técnico responsable</label>
<select id="tecnico_id" name="tecnico_id">
<option value="">Asignar más adelante</option>@foreach($tecnicos as $t)<option value="{{ $t->id }}" @selected(old('tecnico_id')==$t->id)>{{ $t->nombre }}</option>@endforeach</select>
</div>@else<div class="notice span-2" style="margin:0">Esta orden quedará asignada a tu cuenta.</div>@endif<div class="field span-2">
<label for="falla_reportada">Falla reportada por el cliente *</label>
<textarea id="falla_reportada" name="falla_reportada" rows="3" maxlength="5000" required placeholder="Describe el problema del equipo">{{ old('falla_reportada') }}</textarea>
</div>
<div class="field span-2">
<label for="diagnostico">Diagnóstico inicial</label>
<textarea id="diagnostico" name="diagnostico" rows="3" maxlength="5000" placeholder="Completa este campo si el equipo ya fue revisado">{{ old('diagnostico') }}</textarea>
</div>
</div>
<div class="form-actions">
<button class="btn btn-primary">Crear orden de servicio</button>
<a class="btn btn-secondary" href="{{ route('reparaciones.index') }}">Cancelar</a>
</div>
</form>
</section>
</div>
</x-app-layout>
