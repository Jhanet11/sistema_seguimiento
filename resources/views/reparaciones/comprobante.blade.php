<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante #{{ $reparacion->id }}</title>
    <style>
        body { font-family: sans-serif; max-width: 380px; margin: 20px auto; color: #222; font-size: 13px; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 0; }
        .subtitulo { text-align: center; color: #666; font-size: 11px; margin-top: 2px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        td { padding: 3px 0; vertical-align: top; }
        td.label { font-weight: bold; width: 90px; }
        .qr-box { text-align: center; margin: 15px 0; }
        .qr-box p { font-size: 11px; color: #666; margin-top: 5px; }
        .linea { border-top: 1px dashed #999; margin: 15px 0; }
        .footer { text-align: center; font-size: 10px; color: #999; }
        @media print {
            .no-imprimir { display: none; }
        }
    </style>
</head>
<body>

    <h1>EDESSI — Comprobante de Ingreso</h1>
    <p class="subtitulo">Orden #{{ $reparacion->id }} — {{ $reparacion->fecha_ingreso->format('d/m/Y') }}</p>

    <div class="linea"></div>

    <table>
        <tr><td class="label">Cliente:</td><td>{{ $reparacion->equipo->cliente->nombre }}</td></tr>
        <tr><td class="label">C.I.:</td><td>{{ $reparacion->equipo->cliente->ci }}</td></tr>
        <tr><td class="label">Equipo:</td><td>{{ $reparacion->equipo->tipo }} {{ $reparacion->equipo->marca }} {{ $reparacion->equipo->modelo }}</td></tr>
        <tr><td class="label">Falla:</td><td>{{ $reparacion->falla_reportada }}</td></tr>
    </table>

    <div class="linea"></div>

    <div class="qr-box">
        {!! QrCode::size(160)->generate($urlLogin) !!}
        <p>Escanea para consultar el estado de tu reparación<br>ingresando con tu C.I.</p>
    </div>

    <div class="linea"></div>

    <p class="footer">Conserva este comprobante hasta recoger tu equipo.</p>

    <div class="no-imprimir" style="text-align:center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; background:#1f2937; color:white; border:none; border-radius:6px; cursor:pointer;">
            Imprimir comprobante
        </button>
    </div>

</body>
</html>