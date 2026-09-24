<?php

namespace App\Http\Controllers;

use App\Models\HistorialEstado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('usuarios.index', ['usuarios' => Usuario::whereIn('rol', ['admin', 'tecnico'])->withCount('reparaciones')->orderBy('nombre')->paginate(12)]);
    }

    public function create()
    {
        return view('usuarios.create', ['usuario' => new Usuario]);
    }

    public function edit(Usuario $usuario)
    {
        abort_if($usuario->esCliente(), 404);

        return view('usuarios.create', compact('usuario'));
    }

    private function data(Request $request, ?Usuario $u = null)
    {
        return $request->validate(['nombre' => 'required|string|max:255', 'email' => ['required', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($u?->id)], 'rol' => 'required|in:admin,tecnico', 'password' => [$u ? 'nullable' : 'required', 'string', 'min:8', 'confirmed']]);
    }

    public function store(Request $request)
    {
        Usuario::create($this->data($request));

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
    }

    public function update(Request $request, Usuario $usuario)
    {
        abort_if($usuario->esCliente(), 404);
        $data = $this->data($request, $usuario);
        if (($usuario->id === $request->user()->id && $data['rol'] !== 'admin') || ($data['rol'] !== $usuario->rol && $usuario->reparaciones()->where('estado', '!=', 'entregado')->exists())) {
            return back()->with('error', 'No puedes cambiar tu propio rol ni el de un técnico con órdenes activas.');
        }
        if (empty($data['password'])) {
            unset($data['password']);
        } $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function toggleActivo(Usuario $usuario)
    {
        abort_if($usuario->esCliente(), 404);
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }
        if ($usuario->activo && $usuario->reparaciones()->where('estado', '!=', 'entregado')->exists()) {
            return back()->with('error', 'Reasigna las órdenes activas antes de desactivar a este técnico.');
        }
        $usuario->update(['activo' => ! $usuario->activo]);

        return back()->with('success', $usuario->activo ? 'Usuario activado.' : 'Usuario desactivado.');
    }

    public function destroy(Usuario $usuario)
    {
        abort_if($usuario->esCliente(), 404);
        if ($usuario->id === auth()->id() || $usuario->reparaciones()->exists() || $usuario->observaciones()->exists() || HistorialEstado::where('usuario_id', $usuario->id)->exists()) {
            return back()->with('error', 'Esta cuenta debe conservarse por su actividad. Puedes desactivarla si no tiene órdenes pendientes.');
        }
        $usuario->delete();

        return back()->with('success','Usuario sin actividad eliminado.');
    }
}
