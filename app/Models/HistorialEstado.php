<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEstado extends Model
{
    protected $table = 'historial_estados';

    protected $fillable = ['usuario_id', 'estado_anterior', 'estado'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
