<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Lista técnicos y administradores (no clientes, esos se gestionan aparte)
    public function index()
    {
        $usuarios = Usuario::whereIn('rol', ['admin', 'tecnico'])->orderBy('nombre')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'rol' => 'required|in:admin,tecnico',
            'password' => 'required|string|min:6',
        ]);

        Usuario::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'rol' => $data['rol'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    // Activa o desactiva el acceso del usuario (no se elimina, se preserva el historial)
    public function toggleActivo(Usuario $usuario)
    {
        // Evita que el admin se desactive a sí mismo por accidente
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $usuario->update(['activo' => ! $usuario->activo]);

        $mensaje = $usuario->activo ? 'Usuario activado.' : 'Usuario desactivado.';
        return back()->with('success', $mensaje);
    }
}