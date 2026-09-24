<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Orden {{ $reparacion->id }}</title>
<style>
@page{margin:38px 42px}body{font-family:DejaVu Sans,sans-serif;font-size:11px;color:#213651;line-height:1.6}h1{font-size:26px;color:#163961;margin:0}h2{font-size:12px;margin:22px 0 10px;color:#204978}.sub{color:#74849b;font-size:10px}.band{background:#edf3fa;padding:16px;margin:20px 0}.label{color:#74849b;font-size:9px}table{width:100%;border-collapse:collapse}td{padding:7px;vertical-align:top}strong{font-weight:bold}.rule{border-bottom:1px solid #dce4ef;padding-bottom:14px}.qr{width:110px;height:110px}.footer{font-size:9px;color:#76869b;margin-top:28px}.text{white-space:pre-wrap;word-wrap:break-word}tr{page-break-inside:avoid}
</style>
</head>
<body>
<table class="rule">
<tr>
<td style="padding:0">
<h1>EDESSI</h1>
<span class="sub">Servicio técnico · Cochabamba, Bolivia</span>
</td>
<td style="text-align:right">
<strong>ORDEN DE SERVICIO</strong>
<br>
<span style="font-size:19px">#{{ str_pad($reparacion->id,4,'0',STR_PAD_LEFT) }}</span>
<br>
<span class="sub">Ingreso: {{ $reparacion->fecha_ingreso->format('d/m/Y') }}</span>
</td>
</tr>
</table>
<div class="band">
<strong>{{ \App\Models\Reparacion::ESTADOS[$reparacion->estado] }}</strong>
<br>Técnico responsable: {{ $reparacion->tecnico?->nombre??'Por asignar' }}</div>
<h2>CLIENTE Y EQUIPO</h2>
<table>
<tr>
<td>
<span class="label">Cliente</span>
<br>
<strong>{{ $reparacion->equipo->cliente->nombre }}</strong>
</td>
<td>
<span class="label">C.I.</span>
<br>{{ $reparacion->equipo->cliente->ci }}</td>
<td>
<span class="label">Teléfono</span>
<br>{{ $reparacion->equipo->cliente->telefono?:'No registrado' }}</td>
</tr>
<tr>
<td colspan="2">
<span class="label">Equipo</span>
<br>{{ $reparacion->equipo->tipo }} {{ $reparacion->equipo->marca }} {{ $reparacion->equipo->modelo }}</td>
<td>
<span class="label">Número de serie</span>
<br>{{ $reparacion->equipo->numero_serie?:'No registrado' }}</td>
</tr>
</table>
<p>
<span class="label">Accesorios recibidos</span>
<br>{{ $reparacion->equipo->accesorios?:'No registrados' }}</p>
<p>
<span class="label">Estado físico</span>
<br>{{ $reparacion->equipo->estado_visual?:'No registrado' }}</p>
<h2>DETALLE DEL SERVICIO</h2>
<p class="text">
<span class="label">Falla reportada</span>
<br>{{ $reparacion->falla_reportada }}</p>
<p class="text">
<span class="label">Diagnóstico</span>
<br>{{ $reparacion->diagnostico?:'Pendiente de revisión' }}</p>
<table>
<tr>
<td>
<span class="label">Entrega estimada</span>
<br>{{ $reparacion->fecha_estimada?->format('d/m/Y')??'Por definir' }}</td>
<td>
<span class="label">Costo del servicio</span>
<br>{{ $reparacion->costo!==null?'Bs '.number_format($reparacion->costo,2):'Por definir' }}</td>
<td>
<span class="label">Fecha de entrega</span>
<br>{{ $reparacion->fecha_entrega?->format('d/m/Y')??'Pendiente' }}</td>
</tr>
</table>@if($reparacion->repuestos->isNotEmpty())<h2>REPUESTOS UTILIZADOS</h2>@foreach($reparacion->repuestos as $rep)<p>{{ $rep->nombre }} · Cantidad: {{ $rep->pivot->cantidad }}</p>@endforeach @endif<table style="margin-top:25px;border-top:1px solid #dce4ef">
<tr>
<td style="width:125px;padding-top:20px">
<img class="qr" src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(120)->margin(1)->generate($urlSeguimiento)) }}">
</td>
<td style="padding-top:25px">
<strong>Sigue el estado de tu equipo</strong>
<br>Escanea este QR para consultar los avances de tu reparación.<br>
<span class="sub">También puedes consultar con el número de orden y tu C.I. en:</span>
<br>
<a style="font-size:9px;color:#245bad" href="{{ route('seguimiento.index') }}">{{ route('seguimiento.index') }}</a>
</td>
</tr>
</table>
<p class="footer">Conserva este comprobante hasta recoger tu equipo. La fecha estimada puede actualizarse según el diagnóstico y la disponibilidad de repuestos.</p>
</body>
</html>
