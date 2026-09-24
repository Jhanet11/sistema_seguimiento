<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function index()
    {
        return view('seguimiento.index');
    }

    public function buscar(Request $request)
    {
        $data = $request->validate(['orden' => 'required|integer|min:1', 'ci' => 'required|string|max:255']);
        $r = Reparacion::whereKey($data['orden'])->whereHas('equipo.cliente', fn ($q) => $q->where('ci', $data['ci']))->first();
        if (! $r) {
            return back()->withInput()->withErrors(['orden' => 'No encontramos una orden con esos datos. Revisa tu comprobante.']);
        }

        return redirect()->route('seguimiento.show', $r->codigo_seguimiento);
    }

    public function show(string $codigo)
    {
        $reparacion = Reparacion::where('codigo_seguimiento', $codigo)->firstOrFail();

        return response()->view('seguimiento.show', compact('reparacion'))->header('Cache-Control', 'no-store, private')->header('Referrer-Policy', 'no-referrer')->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
