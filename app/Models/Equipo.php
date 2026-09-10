<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Equipo extends Model
{
    protected $fillable = ['cliente_id', 'tipo', 'marca', 'modelo', 'numero_serie'];
 
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
 
    // Historial de reparaciones de este equipo
    public function reparaciones(): HasMany
    {
        return $this->hasMany(Reparacion::class);
    }
}