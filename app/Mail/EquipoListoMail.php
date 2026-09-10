<?php

namespace App\Mail;

use App\Models\Reparacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EquipoListoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reparacion $reparacion;

    public function __construct(Reparacion $reparacion)
    {
        $this->reparacion = $reparacion;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu equipo está listo para recoger — EDESSI',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.equipo-listo',
        );
    }
}