<?php

namespace App\Policies;

use App\Models\Reparacion;
use App\Models\Usuario;

class ReparacionPolicy
{
    public function view(Usuario $u, Reparacion $r): bool
    {
        return $u->esAdmin() || ($u->esTecnico() && ($r->tecnico_id === null || $r->tecnico_id === $u->id)) || ($u->esCliente() && $r->equipo->cliente->usuario_id === $u->id);
    }

    public function update(Usuario $u, Reparacion $r): bool
    {
        return $u->esAdmin() || ($u->esTecnico() && $r->tecnico_id === $u->id);
    }
}
