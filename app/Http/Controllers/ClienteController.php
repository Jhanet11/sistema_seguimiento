<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $q = Cliente::withCount('equipos');
        if ($request->filled('q')) {
            $q->where(fn ($q) => $q->where('nombre', 'like', '%'.$request->q.'%')->orWhere('ci', 'like', '%'.$request->q.'%')->orWhere('telefono', 'like', '%'.$request->q.'%'));
        }

        return view('clientes.index', ['clientes' => $q->latest('id')->paginate(12)->withQueryString()]);
    }

    public function create()
    {
        return view('clientes.create', ['cliente' => new Cliente]);
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.create', compact('cliente'));
    }

    private function validar(Request $request, ?Cliente $cliente = null): array
    {
        return $request->validate(['nombre' => 'required|string|max:255', 'ci' => ['required', 'string', 'max:30', Rule::unique('clientes', 'ci')->ignore($cliente?->id), Rule::unique('usuarios', 'email')->ignore($cliente?->usuario_id)], 'telefono' => 'nullable|string|max:30', 'direccion' => 'nullable|string|max:255', 'correo_notificacion' => 'nullable|email|max:255', 'crear_acceso' => ['sometimes','boolean',Rule::prohibitedIf(!$request->user()->esAdmin())], 'acceso_activo'=>['sometimes','boolean',Rule::prohibitedIf(!$request->user()->esAdmin())], 'password' => [Rule::prohibitedIf(!$request->user()->esAdmin()), Rule::requiredIf($request->boolean('crear_acceso') && ! $cliente?->usuario_id), 'nullable', 'string', 'min:8', 'confirmed']]);
    }

    public function store(Request $request)
    {
        return $this->guardar($request, new Cliente);
    }

    public function update(Request $request, Cliente $cliente)
    {
        return $this->guardar($request, $cliente);
    }

    private function guardar(Request $request, Cliente $cliente)
    {
        $data = $this->validar($request, $cliente);
        DB::transaction(function () use ($data, $cliente) {
            $usuario = $cliente->usuario;
            if (! $usuario && ! empty($data['crear_acceso'])) {
                $usuario = Usuario::create(['nombre' => $data['nombre'], 'email' => $data['ci'], 'password' => $data['password'], 'rol' => 'cliente']);
            } elseif ($usuario) {
                $usuario->update(['nombre' => $data['nombre'], 'email' => $data['ci']]);
                if (! empty($data['password'])) {
                    $usuario->update(['password' => $data['password']]);
                }
            }
            if ($usuario && array_key_exists('acceso_activo',$data)) $usuario->update(['activo'=>$data['acceso_activo']]);
            $cliente->fill(collect($data)->except(['crear_acceso', 'password', 'acceso_activo'])->all());
            $cliente->usuario_id = $usuario?->id;
            $cliente->save();
        });

        return redirect()->route('clientes.show', $cliente)->with('success', 'Datos del cliente guardados correctamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('equipos.reparaciones.tecnico');
        $historialPorMes = $cliente->equipos->flatMap(fn ($e) => $e->reparaciones)->sortByDesc('fecha_ingreso')->groupBy(fn ($r) => $r->fecha_ingreso->translatedFormat('F Y'));

        return view('clientes.show', compact('cliente', 'historialPorMes'));
    }

    public function historialPdf(Cliente $cliente)
    {
        $cliente->load('equipos.reparaciones.tecnico', 'equipos.reparaciones.observaciones', 'equipos.reparaciones.repuestos');
        $reparaciones = $cliente->equipos->flatMap(fn ($e) => $e->reparaciones->map(function ($r) use ($e, $cliente) {
            $r->setRelation('equipo', $e->setRelation('cliente', $cliente));

            return $r;
        }))->sortByDesc('fecha_ingreso');

        return Pdf::loadView('clientes.historial-pdf', ['cliente' => $cliente, 'reparaciones' => $reparaciones, 'fechaGeneracion' => now()])->download('historial-'.$cliente->id.'.pdf');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->equipos()->exists()) {
            return back()->with('error', 'El cliente tiene equipos asociados. Su historial debe conservarse.');
        }
        DB::transaction(function () use ($cliente) {
            $usuario = $cliente->usuario;
            $cliente->delete();
            $usuario?->delete();
        });

        return redirect()->route('clientes.index')->with('success','Cliente sin equipos eliminado.');
    }
}
