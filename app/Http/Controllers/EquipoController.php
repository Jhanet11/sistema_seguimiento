<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        if ($usuario->esCliente()) {
            $equipos = Equipo::whereHas('cliente', fn ($q) => $q->where('usuario_id', $usuario->id))
                ->with('cliente')->get();
        } else {
            $equipos = Equipo::with('cliente')->latest()->get();
        }

        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('equipos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|string',
            'marca' => 'nullable|string',
            'modelo' => 'nullable|string',
            'numero_serie' => 'nullable|string',
        ]);

        Equipo::create($data);

        return redirect()->route('equipos.index')->with('success', 'Equipo registrado.');
    }

    public function show(Equipo $equipo)
    {
        $equipo->load('cliente', 'reparaciones');
        return view('equipos.show', compact('equipo'));
    }
}