<?php
namespace App\Http\Controllers;

use App\Models\Reparacion;
use App\Models\Equipo;
use App\Models\Repuesto;
use App\Notifications\EquipoListoNotification;
use Illuminate\Http\Request;

class ReparacionController extends Controller
{
    // Listado: cambia según el rol
    public function index(Request $request)
    {
        $usuario = $request->user();

        if ($usuario->esAdmin()) {
            $reparaciones = Reparacion::with(['equipo.cliente', 'tecnico'])->latest()->get();
        } elseif ($usuario->esTecnico()) {
            $reparaciones = Reparacion::with(['equipo.cliente'])
                ->where('tecnico_id', $usuario->id)
                ->orWhereNull('tecnico_id') // ve también las libres, para autoasignarse
                ->latest()->get();
        } else { // cliente
            $reparaciones = Reparacion::with(['equipo', 'tecnico'])
                ->whereHas('equipo.cliente', fn ($q) => $q->where('usuario_id', $usuario->id))
                ->latest()->get();
        }

        return view('reparaciones.index', compact('reparaciones'));
    }

    public function create()
    {
        $equipos = Equipo::with('cliente')->get();
        return view('reparaciones.create', compact('equipos'));
    }

    // Comprobante de ingreso imprimible con QR (solo admin)
    public function comprobante(Reparacion $reparacion)
    {
        $reparacion->load('equipo.cliente');
        $urlLogin = route('login');

        return view('reparaciones.comprobante', compact('reparacion', 'urlLogin'));
    }

    // Solo admin registra el ingreso de un equipo (RF03)
    public function store(Request $request)
    {
        $data = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'falla_reportada' => 'required|string',
            'fecha_ingreso' => 'required|date',
        ]);

        Reparacion::create($data + ['estado' => 'recibido']);

        return redirect()->route('reparaciones.index')
            ->with('success', 'Reparación registrada correctamente.');
    }

    public function show(Request $request, Reparacion $reparacion)
    {
        $reparacion->load(['equipo.cliente', 'tecnico', 'observaciones.tecnico', 'repuestos']);
        $catalogoRepuestos = Repuesto::orderBy('nombre')->get();

        // Marca como leídas las notificaciones de esta reparación específica
        $request->user()->unreadNotifications()
            ->where('data->reparacion_id', $reparacion->id)
            ->get()
            ->each->markAsRead();

        return view('reparaciones.show', compact('reparacion', 'catalogoRepuestos'));
    }

    // Admin asigna técnico manualmente (RF05)
    public function asignar(Request $request, Reparacion $reparacion)
    {
        $this->autorizarSoloAdmin($request);

        $data = $request->validate([
            'tecnico_id' => 'required|exists:usuarios,id',
        ]);

        $reparacion->update(['tecnico_id' => $data['tecnico_id']]);

        return back()->with('success', 'Técnico asignado correctamente.');
    }

    // Técnico se autoasigna si la reparación está libre
    public function autoasignar(Request $request, Reparacion $reparacion)
    {
        $usuario = $request->user();

        if (! $usuario->esTecnico()) {
            abort(403);
        }

        if (! $reparacion->estaSinAsignar()) {
            return back()->with('error', 'Esta reparación ya tiene un técnico asignado.');
        }

        $reparacion->update(['tecnico_id' => $usuario->id]);

        return back()->with('success', 'Te has asignado esta reparación.');
    }

    // Técnico actualiza el estado (RF04)
    public function actualizarEstado(Request $request, Reparacion $reparacion)
    {
        $usuario = $request->user();

        if (! $usuario->esAdmin() && $reparacion->tecnico_id !== $usuario->id) {
            abort(403, 'Solo el técnico asignado puede actualizar el estado.');
        }

        $data = $request->validate([
            'estado' => 'required|in:recibido,diagnostico,reparacion,listo,entregado',
        ]);

        $estadoAnterior = $reparacion->estado;

        $reparacion->update($data);

        if ($data['estado'] === 'entregado') {
            $reparacion->update(['fecha_entrega' => now()]);
        }

        // Notificar al cliente solo la primera vez que pasa a "listo"
        if ($data['estado'] === 'listo' && $estadoAnterior !== 'listo') {
            $reparacion->load('equipo.cliente.usuario');
            $usuarioCliente = $reparacion->equipo->cliente->usuario;

            if ($usuarioCliente) {
                $usuarioCliente->notify(new EquipoListoNotification($reparacion));
            }
        }

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    // Admin o técnico asignado registra un repuesto usado (RF09)
    public function agregarRepuesto(Request $request, Reparacion $reparacion)
    {
        $usuario = $request->user();

        if (! $usuario->esAdmin() && $reparacion->tecnico_id !== $usuario->id) {
            abort(403, 'Solo el técnico asignado puede registrar repuestos.');
        }

        $data = $request->validate([
            'repuesto_id' => 'nullable|exists:repuestos,id',
            'repuesto_nuevo' => 'nullable|string|max:255',
            'cantidad' => 'required|integer|min:1',
        ]);

        if (empty($data['repuesto_id']) && empty($data['repuesto_nuevo'])) {
            return back()->with('error', 'Selecciona un repuesto existente o escribe uno nuevo.');
        }

        // Si escribió uno nuevo, se crea (o se reutiliza si ya existe con ese nombre)
        if (! empty($data['repuesto_nuevo'])) {
            $repuesto = Repuesto::firstOrCreate(['nombre' => trim($data['repuesto_nuevo'])]);
        } else {
            $repuesto = Repuesto::findOrFail($data['repuesto_id']);
        }

        // Si el repuesto ya estaba asociado, se suma la cantidad en vez de duplicar la fila
        $existente = $reparacion->repuestos()->where('repuesto_id', $repuesto->id)->first();

        if ($existente) {
            $reparacion->repuestos()->updateExistingPivot($repuesto->id, [
                'cantidad' => $existente->pivot->cantidad + $data['cantidad'],
            ]);
        } else {
            $reparacion->repuestos()->attach($repuesto->id, ['cantidad' => $data['cantidad']]);
        }

        return back()->with('success', 'Repuesto registrado correctamente.');
    }

    private function autorizarSoloAdmin(Request $request): void
    {
        if (! $request->user()->esAdmin()) {
            abort(403);
        }
    }
}