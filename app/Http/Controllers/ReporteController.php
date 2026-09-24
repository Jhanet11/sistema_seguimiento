<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReporteController extends Controller
{
    private function consulta(Request $r)
    {
        $data = $r->validate(['fecha_desde' => 'nullable|date', 'fecha_hasta' => ['nullable', 'date', ...($r->filled('fecha_desde') ? ['after_or_equal:fecha_desde'] : [])], 'estado' => ['nullable', Rule::in(array_keys(Reparacion::ESTADOS))], 'tecnico_id' => ['nullable', Rule::exists('usuarios', 'id')->where('rol', 'tecnico')]]);
        $q = Reparacion::with('equipo.cliente', 'tecnico');
        if ($r->user()->esTecnico()) {
            $q->where('tecnico_id', $r->user()->id);
            $data['tecnico_id'] = $r->user()->id;
        } elseif (! empty($data['tecnico_id'])) {
            $q->where('tecnico_id', $data['tecnico_id']);
        }
        if (! empty($data['fecha_desde'])) {
            $q->whereDate('fecha_ingreso', '>=', $data['fecha_desde']);
        }
        if (! empty($data['fecha_hasta'])) {
            $q->whereDate('fecha_ingreso', '<=', $data['fecha_hasta']);
        }
        if (! empty($data['estado'])) {
            $q->where('estado', $data['estado']);
        }

        return [$q, $data];
    }

    public function index(Request $request)
    {
        [$q,$filtros] = $this->consulta($request);

        return view('reportes.index', ['reparaciones' => $q->latest('id')->paginate(15)->withQueryString(), 'tecnicos' => Usuario::where('rol', 'tecnico')->orderBy('nombre')->get()]);
    }

    public function generar(Request $request)
    {
        [$q,$filtros] = $this->consulta($request);

        return Pdf::loadView('reportes.pdf', ['reparaciones' => $q->latest('id')->get(), 'filtros' => $filtros, 'fechaGeneracion' => now()])->setPaper('a4', 'landscape')->download('reporte-'.today()->toDateString().'.pdf');
    }
}
