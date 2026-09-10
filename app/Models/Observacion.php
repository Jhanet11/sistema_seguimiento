<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Observacion extends Model
{
    protected $table = 'observaciones';
    protected $fillable = ['reparacion_id', 'tecnico_id', 'descripcion'];

    public function reparacion(): BelongsTo
    {
        return $this->belongsTo(Reparacion::class);
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'tecnico_id');
    }
}