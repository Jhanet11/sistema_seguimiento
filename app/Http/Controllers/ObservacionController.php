<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use Illuminate\Http\Request;

class ObservacionController extends Controller
{
    // Técnico agrega una observación a una reparación (RF06)
    public function store(Request $request, Reparacion $reparacion)
    {
        $usuario = $request->user();

        if (! $usuario->esAdmin() && $reparacion->tecnico_id !== $usuario->id) {
            abort(403, 'Solo el técnico asignado puede agregar observaciones.');
        }

        $data = $request->validate([
            'descripcion' => 'required|string',
        ]);

        $reparacion->observaciones()->create([
            'tecnico_id' => $usuario->id,
            'descripcion' => $data['descripcion'],
        ]);

        return back()->with('success', 'Observación agregada.');
    }
}