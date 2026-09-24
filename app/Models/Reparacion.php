<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Reparacion extends Model
{
    public const ESTADOS = ['recibido' => 'Recibido', 'diagnostico' => 'En diagnóstico', 'reparacion' => 'En reparación', 'listo' => 'Listo para entrega', 'entregado' => 'Entregado'];

    protected static function booted(): void
    {
        static::creating(function ($r) {
            $r->codigo_seguimiento = (string) Str::uuid();
        });
    }

    public function historial()
    {
        return $this->hasMany(HistorialEstado::class)->latest('id');
    }

    public function scopeVisiblePara($q, Usuario $u)
    {
        if ($u->esCliente()) {
            $q->whereHas('equipo.cliente', fn ($c) => $c->where('usuario_id', $u->id));
        } elseif ($u->esTecnico()) {
            $q->where(fn ($c) => $c->where('tecnico_id', $u->id)->orWhereNull('tecnico_id'));
        }

        return $q;
    }

    protected $table = 'reparaciones';

    protected $fillable = [
        'equipo_id', 'tecnico_id', 'falla_reportada',
        'estado', 'fecha_ingreso', 'fecha_entrega', 'diagnostico', 'costo', 'fecha_estimada',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_entrega' => 'date', 'fecha_estimada' => 'date', 'costo' => 'decimal:2', 'tecnico_id' => 'integer',
    ];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'tecnico_id');
    }

    public function observaciones(): HasMany
    {
        return $this->hasMany(Observacion::class);
    }

    public function repuestos(): BelongsToMany
    {
        return $this->belongsToMany(Repuesto::class, 'repuestos_usados')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    // Regla de negocio: solo se puede autoasignar si no tiene técnico aún
    public function estaSinAsignar(): bool
    {
        return is_null($this->tecnico_id);
    }
}
