<?php

namespace App\Http\Controllers;

use App\Mail\EquipoListoMail;
use App\Models\Equipo;
use App\Models\Reparacion;
use App\Models\Repuesto;
use App\Models\Usuario;
use App\Notifications\EquipoListoNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReparacionController extends Controller
{
    public function index(Request $request)
    {
        $q = Reparacion::with(['equipo.cliente', 'tecnico'])->visiblePara($request->user());
        if ($request->filled('estado')) {
            $q->where('estado', $request->estado);
        }
        if ($request->filled('q')) {
            $term = $request->string('q')->toString();
            $q->where(fn ($w) => $w->where('id', $term)->orWhereHas('equipo', fn ($e) => $e->where('numero_serie', 'like', "%$term%")->orWhere('marca', 'like', "%$term%")->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', "%$term%")->orWhere('ci', $term))));
        }
        if ($request->boolean('sin_asignar')) {
            $q->whereNull('tecnico_id')->where('estado', '!=', 'entregado');
        }
        $reparaciones = $q->latest('id')->paginate(12)->withQueryString();

        return view('reparaciones.index', compact('reparaciones'));
    }

    public function create()
    {
        return view('reparaciones.create', [
            'equipos' => Equipo::with('cliente')
                ->whereDoesntHave('reparaciones', fn ($q) => $q->where('estado', '!=', 'entregado'))
                ->latest('id')->get(),
            'tecnicos' => $this->tecnicos(),
        ]);
    }

    private function tecnicos()
    {
        return Usuario::where('rol', 'tecnico')->where('activo', true)->orderBy('nombre')->get();
    }

    private function tecnicoRule()
    {
        return Rule::exists('usuarios', 'id')->where('rol', 'tecnico')->where('activo', true);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['equipo_id' => 'required|exists:equipos,id', 'falla_reportada' => 'required|string|max:5000', 'fecha_ingreso' => 'required|date|before_or_equal:today', 'tecnico_id' => ['nullable', $this->tecnicoRule()], 'diagnostico' => 'nullable|string|max:5000', 'fecha_estimada' => 'nullable|date|after_or_equal:fecha_ingreso']);
        if ($request->user()->esTecnico()) {
            $data['tecnico_id'] = $request->user()->id;
        }
        $r = DB::transaction(function () use ($data, $request) {
            Equipo::whereKey($data['equipo_id'])->lockForUpdate()->firstOrFail();
            if (Reparacion::where('equipo_id', $data['equipo_id'])->where('estado', '!=', 'entregado')->exists()) {
                throw ValidationException::withMessages(['equipo_id' => 'Este equipo ya tiene una orden activa. Abre esa orden para continuar.']);
            }
            $r = Reparacion::create($data + ['estado' => 'recibido']);
            $r->historial()->create(['usuario_id' => $request->user()->id, 'estado' => 'recibido']);

            return $r;
        });

        return redirect()->route('reparaciones.show', $r)->with('success', 'Orden de servicio registrada. Ya puedes descargar el comprobante.');
    }

    public function show(Request $request, Reparacion $reparacion)
    {
        Gate::authorize('view', $reparacion);
        $reparacion->load(['equipo.cliente', 'tecnico', 'observaciones.tecnico', 'repuestos', 'historial.usuario']);
        $request->user()->unreadNotifications()->where('data->reparacion_id', $reparacion->id)->get()->each->markAsRead();

        return view('reparaciones.show', ['reparacion' => $reparacion, 'catalogoRepuestos' => Repuesto::orderBy('nombre')->get(), 'tecnicos' => $this->tecnicos()]);
    }

    private function editable(Reparacion $r): void
    {
        Gate::authorize('update', $r);
        abort_if($r->estado === 'entregado', 422, 'La orden ya fue entregada. Registra una nueva recepción para otro servicio.');
    }

    public function update(Request $request, Reparacion $reparacion)
    {
        $this->editable($reparacion);
        $data = $request->validate(['falla_reportada' => 'required|string|max:5000', 'diagnostico' => 'nullable|string|max:5000', 'costo' => 'nullable|numeric|min:0|max:99999999.99', 'fecha_estimada' => ['nullable', 'date', 'after_or_equal:'.$reparacion->fecha_ingreso->toDateString()]]);
        $reparacion->update($data);

        return back()->with('success', 'Detalles del servicio actualizados.');
    }

    public function asignar(Request $request, Reparacion $reparacion)
    {
        $this->editable($reparacion);
        $data = $request->validate(['tecnico_id' => ['required', $this->tecnicoRule()]]);
        $reparacion->update($data);

        return back()->with('success', 'Técnico asignado correctamente.');
    }

    public function autoasignar(Request $request, Reparacion $reparacion)
    {
        $updated = Reparacion::whereKey($reparacion->id)->whereNull('tecnico_id')->where('estado', '!=', 'entregado')->update(['tecnico_id' => $request->user()->id]);

        return back()->with($updated ? 'success' : 'error', $updated ? 'La orden se agregó a tus reparaciones.' : 'La orden ya fue asignada o entregada.');
    }

    public function actualizarEstado(Request $request, Reparacion $reparacion)
    {
        Gate::authorize('update', $reparacion);
        $data = $request->validate(['estado' => ['required', Rule::in(array_keys(Reparacion::ESTADOS))]]);
        $cambio = DB::transaction(function () use ($reparacion, $data, $request) {
            $r = Reparacion::whereKey($reparacion->id)->lockForUpdate()->firstOrFail();
            Gate::authorize('update', $r);
            if ($r->estado === $data['estado']) {
                return false;
            }
            if ($r->estado === 'entregado') {
                throw ValidationException::withMessages(['estado' => 'Una orden entregada no se puede reabrir. Crea una nueva orden.']);
            }
            if ($data['estado'] === 'entregado' && $r->estado !== 'listo') {
                throw ValidationException::withMessages(['estado' => 'Marca el equipo como listo antes de entregarlo.']);
            }
            $anterior = $r->estado;
            $r->update(['estado' => $data['estado'], 'fecha_entrega' => $data['estado'] === 'entregado' ? today() : null]);
            $r->historial()->create(['usuario_id' => $request->user()->id, 'estado_anterior' => $anterior, 'estado' => $r->estado]);

            return true;
        });
        if ($cambio && $data['estado'] === 'listo') {
            $reparacion->refresh()->load('equipo.cliente.usuario');
            $cliente = $reparacion->equipo->cliente;
            if ($cliente->usuario) {
                $cliente->usuario->notify(new EquipoListoNotification($reparacion));
            }
            if ($cliente->correo_notificacion) {
                Mail::to($cliente->correo_notificacion)->queue((new EquipoListoMail($reparacion))->afterCommit());
            }
        }

        return back()->with('success', $cambio ? 'Estado actualizado correctamente.' : 'La orden ya se encuentra en ese estado.');
    }

    public function agregarRepuesto(Request $request, Reparacion $reparacion)
    {
        $this->editable($reparacion);
        $data = $request->validate(['repuesto_id' => 'nullable|required_without:repuesto_nuevo|exists:repuestos,id', 'repuesto_nuevo' => 'nullable|required_without:repuesto_id|string|max:255', 'cantidad' => 'required|integer|min:1|max:10000']);
        DB::transaction(function () use ($data, $reparacion) {
            Reparacion::whereKey($reparacion->id)->lockForUpdate()->firstOrFail();
            $rep = ! empty($data['repuesto_nuevo']) ? Repuesto::firstOrCreate(['nombre' => trim($data['repuesto_nuevo'])]) : Repuesto::findOrFail($data['repuesto_id']);
            $actual = $reparacion->repuestos()->where('repuesto_id', $rep->id)->first();
            $reparacion->repuestos()->syncWithoutDetaching([$rep->id => ['cantidad' => ($actual?->pivot->cantidad ?? 0) + $data['cantidad']]]);
        });

        return back()->with('success', 'Repuesto registrado correctamente.');
    }

    public function comprobante(Reparacion $reparacion)
    {
        Gate::authorize('view', $reparacion);
        $reparacion->load('equipo.cliente', 'tecnico', 'repuestos');
        $urlSeguimiento = route('seguimiento.show', $reparacion->codigo_seguimiento);

        return Pdf::loadView('reparaciones.comprobante', compact('reparacion', 'urlSeguimiento'))->download('orden-'.$reparacion->id.'.pdf');
    }

    public function destroy(Reparacion $reparacion)
    {
        if ($reparacion->estado !== 'recibido' || $reparacion->observaciones()->exists() || $reparacion->repuestos()->exists() || $reparacion->historial()->count() > 1) {
            return back()->with('error','La orden tiene actividad técnica y debe conservarse en el historial.');
        }
        $reparacion->delete();

        return redirect()->route('reparaciones.index')->with('success','Orden sin actividad eliminada.');
    }
}
