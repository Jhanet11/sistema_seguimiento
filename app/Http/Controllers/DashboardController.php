<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Reparacion;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        if ($usuario->esAdmin()) {
            return view('dashboard.admin', [
                'totalReparaciones' => Reparacion::count(),
                'porEstado' => Reparacion::selectRaw('estado, count(*) as total')
                    ->groupBy('estado')->pluck('total', 'estado'),
                'sinAsignar' => Reparacion::whereNull('tecnico_id')->count(),
                'totalClientes' => Cliente::count(),
                'totalEquipos' => Equipo::count(),
                'ultimasReparaciones' => Reparacion::with(['equipo.cliente', 'tecnico'])
                    ->latest()->take(5)->get(),
            ]);
        }

        if ($usuario->esTecnico()) {
            return view('dashboard.tecnico', [
                'misReparaciones' => Reparacion::with('equipo.cliente')
                    ->where('tecnico_id', $usuario->id)
                    ->whereNotIn('estado', ['entregado'])
                    ->latest()->get(),
                'sinAsignar' => Reparacion::with('equipo.cliente')
                    ->whereNull('tecnico_id')->latest()->get(),
            ]);
        }

        // cliente
        return view('dashboard.cliente', [
            'equipos' => Equipo::with('reparaciones')
                ->whereHas('cliente', fn ($q) => $q->where('usuario_id', $usuario->id))
                ->get(),
        ]);
    }
}