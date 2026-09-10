<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
 
class Repuesto extends Model
{
    protected $fillable = ['nombre', 'descripcion'];
 
    public function reparaciones(): BelongsToMany
    {
        return $this->belongsToMany(Reparacion::class, 'repuestos_usados')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}