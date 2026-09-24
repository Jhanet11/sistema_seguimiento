<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate(['email' => 'required|string|max:255']);
        $u = Usuario::where('email', $request->email)->first();
        if (! $u) {
            $matches = Usuario::where('rol', 'cliente')->whereHas('cliente', fn ($c) => $c->where('correo_notificacion', $request->email))->get();
            $u = $matches->count() === 1 ? $matches->first() : null;
        }
        if ($u && $u->activo && $u->routeNotificationForMail(null)) {
            Password::sendResetLink(['email' => $u->email]);
        }

        return back()->with('status', 'Si existe una cuenta con correo de contacto, recibirás un enlace. Si no tienes correo registrado, solicita ayuda al taller.');
    }
}
