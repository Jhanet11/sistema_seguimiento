<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 0; }
        .subtitulo { color: #666; margin-top: 4px; margin-bottom: 15px; }
        .datos-cliente { margin-bottom: 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; font-size: 10px; vertical-align: top; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 20px; font-size: 10px; color: #999; }
    </style>
</head>
<body>

    <h1>EDESSI — Historial de Reparaciones</h1>
    <p class="subtitulo">Generado el {{ $fechaGeneracion->format('d/m/Y H:i') }}</p>

    <div class="datos-cliente">
        <strong>Cliente:</strong> {{ $cliente->nombre }} &nbsp;|&nbsp;
        <strong>C.I.:</strong> {{ $cliente->ci }} &nbsp;|&nbsp;
        <strong>Teléfono:</strong> {{ $cliente->telefono ?? '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha ingreso</th>
                <th>Equipo</th>
                <th>Falla reportada</th>
                <th>Técnico</th>
                <th>Estado</th>
                <th>Fecha entrega</th>
                <th>Repuestos</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reparaciones as $r)
                <tr>
                    <td>{{ $r->fecha_ingreso->format('d/m/Y') }}</td>
                    <td>{{ $r->equipo->tipo }} {{ $r->equipo->marca }} {{ $r->equipo->modelo }}</td>
                    <td>{{ $r->falla_reportada }}</td>
                    <td>{{ $r->tecnico->nombre ?? 'Sin asignar' }}</td>
                    <td>{{ ucfirst($r->estado) }}</td>
                    <td>{{ $r->fecha_entrega ? $r->fecha_entrega->format('d/m/Y') : '-' }}</td>
                    <td>
                        @forelse ($r->repuestos as $rep)
                            {{ $rep->nombre }} ({{ $rep->pivot->cantidad }})@if (!$loop->last), @endif
                        @empty
                            -
                        @endforelse
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Este cliente no tiene reparaciones registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">Total de reparaciones: {{ $reparaciones->count() }}</p>

</body>
</html>