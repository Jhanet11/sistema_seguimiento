<x-app-layout>
<x-slot name="header">
<div>
<h1>{{ $equipo->exists?'Editar equipo':'Registrar equipo' }}</h1>
<p>Identifica el dispositivo y las condiciones de recepción.</p>
</div>
<a class="btn btn-secondary" href="{{ route('equipos.index') }}">Volver</a>
</x-slot>
<div class="form-shell">
<section class="panel">
<div class="panel-head">
<h2>Información del equipo</h2>
</div>
<form class="panel-body" method="POST" action="{{ $equipo->exists?route('equipos.update',$equipo):route('equipos.store') }}">@csrf @if($equipo->exists) @method('PATCH') @endif<div class="form-grid">
<div class="field span-2">
<label for="cliente_id">Cliente *</label>
<select name="cliente_id" id="cliente_id" required>
<option value="">Selecciona un cliente</option>@foreach($clientes as $c)<option value="{{ $c->id }}" @selected(old('cliente_id',$equipo->cliente_id??request('cliente_id'))==$c->id)>{{ $c->nombre }} · C.I. {{ $c->ci }}</option>@endforeach</select>
<a class="link" href="{{ route('clientes.create') }}">+ Registrar un cliente nuevo</a>
</div>
<div class="field">
<label for="tipo">Tipo de equipo *</label>
<input id="tipo" name="tipo" type="text" value="{{ old('tipo', $equipo->tipo) }}" required placeholder="Laptop, PC, impresora…" maxlength="100">
</div>
<div class="field">
<label for="marca">Marca</label>
<input id="marca" name="marca" type="text" value="{{ old('marca', $equipo->marca) }}"  maxlength="100">
</div>
<div class="field">
<label for="modelo">Modelo</label>
<input id="modelo" name="modelo" type="text" value="{{ old('modelo', $equipo->modelo) }}"  maxlength="100">
</div>
<div class="field">
<label for="numero_serie">Número de serie</label>
<input id="numero_serie" name="numero_serie" type="text" value="{{ old('numero_serie', $equipo->numero_serie) }}"  maxlength="255">
</div>
<div class="field span-2">
<label for="accesorios">Accesorios recibidos</label>
<textarea id="accesorios" name="accesorios" rows="2" maxlength="2000" placeholder="Cargador, cable de alimentación, funda…">{{ old('accesorios',$equipo->accesorios) }}</textarea>
</div>
<div class="field span-2">
<label for="estado_visual">Estado físico del equipo</label>
<textarea id="estado_visual" name="estado_visual" rows="3" maxlength="2000" placeholder="Describe golpes, rayones u otras condiciones visibles">{{ old('estado_visual',$equipo->estado_visual) }}</textarea>
</div>
</div>
<div class="form-actions">
<button class="btn btn-primary">Guardar equipo</button>
<a class="btn btn-secondary" href="{{ route('equipos.index') }}">Cancelar</a>
</div>
</form>
</section>
</div>
</x-app-layout>
