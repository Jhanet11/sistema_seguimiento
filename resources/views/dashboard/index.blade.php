<x-app-layout>
<x-slot name="header">
<div>
<span class="eyebrow muted" style="display:block;margin-bottom:9px">{{ auth()->user()->esCliente()?'TUS SERVICIOS':'VISTA GENERAL' }}</span>
<h1>{{ auth()->user()->esCliente()?'El estado de tus equipos':'Resumen del taller' }}</h1>
<p>Hola, {{ explode(' ',auth()->user()->nombre)[0] }}. {{ auth()->user()->esCliente()?'Sigue cada etapa de tus reparaciones.':'Así va el trabajo en EDESSI.' }}</p>
</div>@if(!auth()->user()->esCliente())<a class="btn btn-primary" href="{{ route('reparaciones.create') }}">
<x-icon name="plus"/>Nueva reparación</a>@endif</x-slot>
@if($vencidas)<div class="banner">
<x-icon name="clock"/>
<span>
<strong>{{ $vencidas }} {{ $vencidas===1?'orden requiere':'órdenes requieren' }} atención.</strong> La fecha estimada ya pasó y el servicio sigue pendiente.</span>
</div>@endif
<div class="stats">
@php($cards=[['Órdenes registradas',$totalReparaciones,'tool','Historial de servicios'],['En proceso',($porEstado['diagnostico']??0)+($porEstado['reparacion']??0),'clock','Diagnóstico y reparación'],['Listos para entrega',$porEstado['listo']??0,'check','Equipos que pueden recogerse'],['Equipos registrados',$totalEquipos,'monitor',auth()->user()->esCliente()?'Asociados a tu cuenta':($totalClientes.' clientes en el taller')]])
@foreach($cards as [$label,$value,$icon,$note])<div class="panel stat">
<div class="stat-head">
<span>{{ $label }}</span>
<span class="stat-icon">
<x-icon :name="$icon"/>
</span>
</div>
<div class="stat-value">{{ $value }}</div>
<p class="stat-note">{{ $note }}</p>
</div>@endforeach</div>
<div class="dashboard-grid">
<div>
<section class="panel">
<div class="panel-head">
<div>
<h2>Reparaciones recientes</h2>
<p class="muted" style="font-size:11px;margin-top:4px">Los últimos equipos recibidos y su estado actual</p>
</div>
<a class="link" href="{{ route('reparaciones.index') }}">Ver todas →</a>
</div>
<x-repair-table :reparaciones="$ultimasReparaciones"/>
<div class="table-foot">Mostrando {{ $ultimasReparaciones->count() }} de {{ $totalReparaciones }} órdenes registradas</div>
</section>
<div class="welcome-note">
<x-icon name="shield"/>
<div>
<strong>Un historial que acompaña a cada equipo.</strong>Consulta los diagnósticos, las observaciones y los cambios de estado desde el detalle de cada orden.</div>
</div>
</div>
<aside class="stack dashboard-aside">
<section class="panel">
<div class="panel-head">
<h2>Estado del taller</h2>
<x-icon name="grid" class="muted"/>
</div>
<div class="panel-body" style="padding-top:7px">@foreach(\App\Models\Reparacion::ESTADOS as $key=>$label)<div class="workflow-row">
<span>{{ $label }}</span>
<strong>{{ $porEstado[$key]??0 }}</strong>
</div>
<div class="bar">
<span style="width:{{ $totalReparaciones?round(($porEstado[$key]??0)/$totalReparaciones*100):0 }}%">
</span>
</div>@endforeach</div>
</section>
<section class="panel">
<div class="panel-head">
<h2>Accesos rápidos</h2>
</div>
<div class="panel-body" style="padding-top:7px">@if(!auth()->user()->esCliente())<a class="quick-link" href="{{ route('clientes.create') }}">
<x-icon name="users"/>
<span>Registrar cliente<small>Comienza una nueva atención</small>
</span>
<span class="arrow">↗</span>
</a>
<a class="quick-link" href="{{ route('equipos.create') }}">
<x-icon name="monitor"/>
<span>Recibir equipo<small>Datos, accesorios y estado físico</small>
</span>
<span class="arrow">↗</span>
</a>
<a class="quick-link" href="{{ route('reportes.index') }}">
<x-icon name="file"/>
<span>Generar reporte<small>Consulta y descarga en PDF</small>
</span>
<span class="arrow">↗</span>
</a>@else<a class="quick-link" href="{{ route('reparaciones.index') }}">
<x-icon name="tool"/>
<span>Ver mis reparaciones</span>
<span class="arrow">↗</span>
</a>
<a class="quick-link" href="{{ route('profile.edit') }}">
<x-icon name="users"/>
<span>Actualizar mi perfil</span>
<span class="arrow">↗</span>
</a>@endif</div>
</section>@if($sinAsignar && !auth()->user()->esCliente())<a class="banner" style="margin-bottom:0" href="{{ route('reparaciones.index',['sin_asignar'=>1]) }}">
<x-icon name="clock"/>
<span>
<strong>{{ $sinAsignar }} órdenes sin asignar</strong>
<br>Organiza la carga del equipo →</span>
</a>@endif</aside>
</div>
</x-app-layout>
