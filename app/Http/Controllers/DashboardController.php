<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Reparacion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $r)
    {
        $u = $r->user();
        $base = Reparacion::visiblePara($u);

        return view('dashboard.index', [
            'porEstado' => (clone $base)->selectRaw('estado,count(*) as total')->groupBy('estado')->pluck('total', 'estado'),
            'totalReparaciones' => (clone $base)->count(),
            'sinAsignar' => (clone $base)->whereNull('tecnico_id')->where('estado', '!=', 'entregado')->count(),
            'ultimasReparaciones' => (clone $base)->with('equipo.cliente', 'tecnico')->latest('id')->take(6)->get(),
            'vencidas' => (clone $base)->whereNotIn('estado', ['listo', 'entregado'])->whereDate('fecha_estimada', '<', today())->count(),
            'totalClientes' => $u->esCliente() ? null : Cliente::count(),
            'totalEquipos' => $u->esCliente() ? Equipo::whereHas('cliente', fn ($c) => $c->where('usuario_id', $u->id))->count() : Equipo::count(),
        ]);
    }
}
