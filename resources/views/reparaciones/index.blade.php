<x-app-layout>
<x-slot name="header">
<div>
<h1>{{ auth()->user()->esCliente()?'Mis reparaciones':'Reparaciones' }}</h1>
<p>Consulta las órdenes de servicio y sigue su avance.</p>
</div>@if(!auth()->user()->esCliente())<a class="btn btn-primary" href="{{ route('reparaciones.create') }}">
<x-icon name="plus"/>Nueva reparación</a>@endif</x-slot>
<section class="panel">
<form class="filters" method="GET">
<div class="field" style="flex:1;min-width:190px">
<label for="q">Buscar orden</label>
<input id="q" name="q" value="{{ request('q') }}" placeholder="N.º de orden, cliente, C.I. o serie">
</div>
<div class="field">
<label for="estado">Estado</label>
<select id="estado" name="estado">
<option value="">Todos los estados</option>@foreach(\App\Models\Reparacion::ESTADOS as $key=>$label)<option value="{{ $key }}" @selected(request('estado')===$key)>{{ $label }}</option>@endforeach</select>
</div>
<button class="btn btn-primary">
<x-icon name="search"/>Buscar</button>
<a class="btn btn-secondary" href="{{ route('reparaciones.index') }}">Limpiar</a>@if(request('sin_asignar'))<input type="hidden" name="sin_asignar" value="1">
<span class="badge badge-diagnostico">Solo sin asignar</span>@endif</form>
<x-repair-table :reparaciones="$reparaciones"/>
<div class="table-foot">{{ $reparaciones->links() }}</div>
</section>
</x-app-layout>
