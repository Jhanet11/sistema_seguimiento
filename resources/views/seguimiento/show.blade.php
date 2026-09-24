<x-guest-layout>
<span class="eyebrow muted">SEGUIMIENTO DEL SERVICIO</span>
<h1 style="margin-top:12px">Orden #{{ str_pad($reparacion->id,4,'0',STR_PAD_LEFT) }}</h1>
<p class="intro">{{ $reparacion->equipo->tipo }} {{ $reparacion->equipo->marca }} {{ $reparacion->equipo->modelo }}</p>
<x-status :estado="$reparacion->estado"/>@include('components.estado-progreso',['estado'=>$reparacion->estado])<div class="tracking-card">
<p>
<strong>Falla reportada</strong>
<br>{{ $reparacion->falla_reportada }}</p>
<p>
<strong>Fecha de ingreso:</strong> {{ $reparacion->fecha_ingreso->format('d/m/Y') }}</p>
<p>
<strong>Técnico:</strong> {{ $reparacion->tecnico?->nombre??'Por asignar' }}</p>@if($reparacion->fecha_estimada)<p>
<strong>Entrega estimada:</strong> {{ $reparacion->fecha_estimada->format('d/m/Y') }}</p>@endif @if($reparacion->fecha_entrega)<p>
<strong>Entregado el:</strong> {{ $reparacion->fecha_entrega->format('d/m/Y') }}</p>@endif</div>@if($reparacion->estado==='listo')<div class="notice">Tu equipo está listo para recoger. Presenta tu comprobante en el taller.</div>@endif<a class="btn btn-secondary" href="{{ route('seguimiento.index') }}">Consultar otra orden</a>
<p class="footnote">Conserva este enlace para revisar los próximos avances.</p>
</x-guest-layout>
