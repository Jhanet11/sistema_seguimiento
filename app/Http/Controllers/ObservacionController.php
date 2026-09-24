<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ObservacionController extends Controller
{
    // Técnico agrega una observación a una reparación (RF06)
    public function store(Request $request, Reparacion $reparacion)
    {
        Gate::authorize('update', $reparacion);
        abort_if($reparacion->estado === 'entregado', 422, 'La orden está cerrada.');
        $usuario = $request->user();

        if (! $usuario->esAdmin() && $reparacion->tecnico_id !== $usuario->id) {
            abort(403, 'Solo el técnico asignado puede agregar observaciones.');
        }

        $data = $request->validate([
            'descripcion' => 'required|string|max:5000',
        ]);

        $reparacion->observaciones()->create([
            'tecnico_id' => $usuario->id,
            'descripcion' => $data['descripcion'],
        ]);

        return back()->with('success', 'Observación agregada.');
    }
}
