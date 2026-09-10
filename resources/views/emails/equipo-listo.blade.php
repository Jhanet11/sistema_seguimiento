<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; color: #333; max-width: 500px; margin: 0 auto; padding: 20px;">

    <h2 style="color: #1f2937;">Tu equipo está listo 🎉</h2>

    <p>Hola {{ $reparacion->equipo->cliente->nombre }},</p>

    <p>
        Te informamos que tu equipo
        <strong>{{ $reparacion->equipo->tipo }} {{ $reparacion->equipo->marca }} {{ $reparacion->equipo->modelo }}</strong>
        ya está listo para ser recogido en nuestro taller.
    </p>

    <div style="background: #f3f4f6; border-radius: 8px; padding: 15px; margin: 20px 0;">
        <p style="margin: 4px 0;"><strong>Falla reportada:</strong> {{ $reparacion->falla_reportada }}</p>
        <p style="margin: 4px 0;"><strong>Técnico responsable:</strong> {{ $reparacion->tecnico->nombre ?? 'Equipo EDESSI' }}</p>
        <p style="margin: 4px 0;"><strong>Fecha de ingreso:</strong> {{ $reparacion->fecha_ingreso->format('d/m/Y') }}</p>
    </div>

    <p>Puedes ingresar al sistema con tu C.I. para ver el detalle completo de tu reparación.</p>

    <p style="margin-top: 30px; font-size: 12px; color: #9ca3af;">
        Este es un mensaje automático de EDESSI. Por favor no respondas a este correo.
    </p>

</body>
</html>