<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = ['nombre', 'email', 'password', 'rol', 'activo'];

    protected $hidden = ['password', 'remember_token'];

    // Si el usuario es técnico: reparaciones que tiene asignadas
    public function reparaciones(): HasMany
    {
        return $this->hasMany(Reparacion::class, 'tecnico_id');
    }

    // Si el usuario es cliente: su registro de cliente asociado
    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'usuario_id');
    }

    public function observaciones(): HasMany
    {
        return $this->hasMany(Observacion::class, 'tecnico_id');
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esTecnico(): bool
    {
        return $this->rol === 'tecnico';
    }

    public function esCliente(): bool
    {
        return $this->rol === 'cliente';
    }
}