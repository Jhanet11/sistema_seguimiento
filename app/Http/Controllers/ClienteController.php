<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('usuario')->latest()->get();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    // Admin registra cliente y opcionalmente le crea su cuenta de acceso (login = C.I.)
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'ci' => 'required|string|unique:clientes,ci',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'correo_notificacion' => 'nullable|email',
            'crear_acceso' => 'boolean',
        ]);

        $usuarioId = null;

        if (! empty($data['crear_acceso'])) {
            $usuario = Usuario::create([
                'nombre' => $data['nombre'],
                'email' => $data['ci'], // el C.I. funciona como usuario de acceso
                'password' => Hash::make('password'), // el cliente debe cambiarla luego
                'rol' => 'cliente',
            ]);
            $usuarioId = $usuario->id;
        }

        Cliente::create([
            'usuario_id' => $usuarioId,
            'nombre' => $data['nombre'],
            'ci' => $data['ci'],
            'telefono' => $data['telefono'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'correo_notificacion' => $data['correo_notificacion'] ?? null,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('equipos.reparaciones.tecnico', 'equipos.reparaciones.observaciones', 'equipos.reparaciones.repuestos');

        // Todas las reparaciones de todos sus equipos, juntas y ordenadas de más reciente a más antigua
        $todasReparaciones = $cliente->equipos
            ->flatMap(fn ($equipo) => $equipo->reparaciones->map(function ($r) use ($equipo) {
                $r->setRelation('equipo', $equipo);
                return $r;
            }))
            ->sortByDesc('fecha_ingreso');

        // Agrupadas por mes/año, como un estado de cuenta (ej: "Agosto 2026")
        $historialPorMes = $todasReparaciones->groupBy(function ($r) {
            return $r->fecha_ingreso->translatedFormat('F Y');
        });

        return view('clientes.show', compact('cliente', 'historialPorMes'));
    }

    // Descarga en PDF el historial completo de reparaciones de un cliente
   public function historialPdf(Cliente $cliente)
    {
        $cliente->load('equipos.reparaciones.tecnico', 'equipos.reparaciones.observaciones', 'equipos.reparaciones.repuestos');

        // AGREGADO $cliente en el 'use ($equipo, $cliente)'
        $reparaciones = $cliente->equipos
            ->flatMap(fn ($equipo) => $equipo->reparaciones->map(function ($r) use ($equipo, $cliente) {
                $r->setRelation('equipo', $equipo->setRelation('cliente', $cliente));
                return $r;
            }))
            ->sortByDesc('fecha_ingreso');

        $pdf = Pdf::loadView('clientes.historial-pdf', [
            'cliente' => $cliente,
            'reparaciones' => $reparaciones,
            'fechaGeneracion' => now(),
        ]);

        return $pdf->download('historial_'.\Illuminate\Support\Str::slug($cliente->nombre).'.pdf');
    }
}