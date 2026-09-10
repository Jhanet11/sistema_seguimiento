<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Reparacion extends Model
{
    protected $table = 'reparaciones';
   
    protected $fillable = [
        'equipo_id', 'tecnico_id', 'falla_reportada',
        'estado', 'fecha_ingreso', 'fecha_entrega',
    ];
 
    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_entrega' => 'date',
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