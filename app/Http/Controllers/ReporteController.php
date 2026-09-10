<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    // Pantalla con filtros
    public function index()
    {
        $tecnicos = Usuario::where('rol', 'tecnico')->get();
        return view('reportes.index', compact('tecnicos'));
    }

    // Genera y descarga el PDF con los filtros aplicados
    public function generar(Request $request)
    {
        $data = $request->validate([
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date',
            'tecnico_id' => 'nullable|exists:usuarios,id',
            'estado' => 'nullable|in:recibido,diagnostico,reparacion,listo,entregado',
        ]);

        $query = Reparacion::with(['equipo.cliente', 'tecnico']);

        if (! empty($data['fecha_desde'])) {
            $query->whereDate('fecha_ingreso', '>=', $data['fecha_desde']);
        }
        if (! empty($data['fecha_hasta'])) {
            $query->whereDate('fecha_ingreso', '<=', $data['fecha_hasta']);
        }
        if (! empty($data['tecnico_id'])) {
            $query->where('tecnico_id', $data['tecnico_id']);
        }
        if (! empty($data['estado'])) {
            $query->where('estado', $data['estado']);
        }

        $reparaciones = $query->latest()->get();

        $pdf = Pdf::loadView('reportes.pdf', [
            'reparaciones' => $reparaciones,
            'filtros' => $data,
            'fechaGeneracion' => now(),
        ]);

        return $pdf->download('reporte_reparaciones_'.now()->format('Y-m-d_His').'.pdf');
    }
}