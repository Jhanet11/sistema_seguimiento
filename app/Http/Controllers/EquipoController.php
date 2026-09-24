<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $q = Equipo::with('cliente')->withCount('reparaciones');
        if ($request->user()->esCliente()) {
            $q->whereHas('cliente', fn ($c) => $c->where('usuario_id', $request->user()->id));
        }
        if ($request->filled('q')) {
            $q->where(fn ($w) => $w->where('marca', 'like', '%'.$request->q.'%')->orWhere('numero_serie', 'like', '%'.$request->q.'%')->orWhere('tipo', 'like', '%'.$request->q.'%')->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', '%'.$request->q.'%')));
        }

        return view('equipos.index', ['equipos' => $q->latest('id')->paginate(12)->withQueryString()]);
    }

    public function create()
    {
        return view('equipos.create', ['equipo' => new Equipo, 'clientes' => Cliente::orderBy('nombre')->get()]);
    }

    public function edit(Equipo $equipo)
    {
        return view('equipos.create', ['equipo' => $equipo, 'clientes' => Cliente::orderBy('nombre')->get()]);
    }

    private function data(Request $request)
    {
        return $request->validate(['cliente_id' => 'required|exists:clientes,id', 'tipo' => 'required|string|max:100', 'marca' => 'nullable|string|max:100', 'modelo' => 'nullable|string|max:100', 'numero_serie' => 'nullable|string|max:255', 'accesorios' => 'nullable|string|max:2000', 'estado_visual' => 'nullable|string|max:2000']);
    }

    public function store(Request $request)
    {
        $e = Equipo::create($this->data($request));

        return redirect()->route('equipos.show', $e)->with('success', 'Equipo registrado. Ya puedes abrir su orden de servicio.');
    }

    public function update(Request $request, Equipo $equipo)
    {
        $data = $this->data($request);
        if ((int) $data['cliente_id'] !== $equipo->cliente_id && $equipo->reparaciones()->exists()) {
            return back()->withInput()->withErrors(['cliente_id' => 'Un equipo con historial no puede cambiar de propietario.']);
        }
        $equipo->update($data);

        return redirect()->route('equipos.show', $equipo)->with('success', 'Equipo actualizado.');
    }

    public function show(Request $request, Equipo $equipo)
    {
        abort_if($request->user()->esCliente() && $equipo->cliente->usuario_id !== $request->user()->id, 403);
        $equipo->load(['cliente', 'reparaciones' => fn ($q) => $q->visiblePara($request->user())->with('tecnico')->latest('id')]);

        return view('equipos.show', compact('equipo'));
    }

    public function destroy(Equipo $equipo)
    {
        if ($equipo->reparaciones()->exists()) {
            return back()->with('error', 'El equipo tiene reparaciones. Su historial debe conservarse.');
        }
        $equipo->delete();

        return redirect()->route('equipos.index')->with('success','Equipo sin reparaciones eliminado.');
    }
}
