<?php

namespace App\Notifications;

use App\Mail\EquipoListoMail;
use App\Models\Reparacion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EquipoListoNotification extends Notification
{
    use Queueable;

    public Reparacion $reparacion;

    public function __construct(Reparacion $reparacion)
    {
        $this->reparacion = $reparacion;
    }

    // Envía por 'database' siempre; por 'mail' solo si el cliente tiene correo de contacto
    public function via($notifiable): array
    {
        $canales = ['database'];

        // El correo se encola por separado; un fallo SMTP no revierte la actualización.

        return $canales;
    }

    public function toMail($notifiable)
    {
        return (new EquipoListoMail($this->reparacion))
            ->to($notifiable->cliente->correo_notificacion);
    }

    // Lo que se guarda para mostrar en la campanita/notificación web
    public function toDatabase($notifiable): array
    {
        return [
            'reparacion_id' => $this->reparacion->id,
            'equipo' => $this->reparacion->equipo->tipo.' '.$this->reparacion->equipo->marca,
            'mensaje' => 'Tu equipo '.$this->reparacion->equipo->tipo.' '.$this->reparacion->equipo->marca.' está listo para recoger.',
        ];
    }
}
