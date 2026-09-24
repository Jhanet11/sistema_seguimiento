<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected function casts(): array
    {
        return ['activo' => 'boolean', 'password' => 'hashed'];
    }

    public function routeNotificationForMail($notification)
    {
        return $this->esCliente() ? $this->cliente?->correo_notificacion : $this->email;
    }

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
