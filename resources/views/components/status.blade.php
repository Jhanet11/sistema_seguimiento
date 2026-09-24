@props(['estado'])<span class="badge badge-{{ $estado }}">{{ \App\Models\Reparacion::ESTADOS[$estado] ?? $estado }}</span>
